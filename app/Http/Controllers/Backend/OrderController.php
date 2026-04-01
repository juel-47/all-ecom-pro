<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\OrderDataTable;
use App\DataTables\statusOrdersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\SteadfastSetting;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @param OrderDataTable $dataTable The data table object.
     * @return \Illuminate\Http\Response The rendered view response.
     */

    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('backend.orders.index');
    }
    /**
     * Show the details of the order with the given ID.
     *
     * @param string $id The ID of the order to show.
     * @return \Illuminate\Http\Response The rendered view response.
     */

    public function show(string $id)
    {
        $order = Order::findOrFail($id);
        // dd($order);
        $order_status = OrderStatus::where('status', 1)->get();
        return view('backend.orders.show', compact('order', 'order_status'));
    }

    /**
     * Destroy the specified order.
     *
     * @param string $id The ID of the order to delete.
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the success status and a message.
     */

    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        // delete order product :
        $order->orderProducts()->delete();
        //delete transaction :
        $order->transaction()->delete();
        $order->delete();
        return response(['status' => 'success', 'message' => 'Order Deleted Successfully!']);
    }

    /**
     * Render the orders status view with the given status ID.
     *
     * @param statusOrdersDataTable $dataTable The data table object.
     * @param int $statusId The ID of the status to filter by.
     * @return \Illuminate\Http\Response The rendered view response.
     */

    public function statusOrders(statusOrdersDataTable $dataTable, $statusId)
    {
        // dd($statusId);
        return $dataTable->setStatus($statusId)->render('backend.orders.status', [
            'statuses' => OrderStatus::where('status', 1)->get(),
            'selected_status' => $statusId
        ]);
    }

    /**
     * Changes the payment status of an order.
     *
     * @param Request $request The request object containing the order ID and the new payment status.
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the success status and a message.
     */
    
    public function changePaymentStatus(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $order->payment_status = $request->status;
        $order->save();
        return response(['status' => 'success', 'message' => 'Updated Payment Status Successfully']);
    }

    /**
     * Change the status of an order.
     *
     * @param Request $request The request object containing the order ID and the new status.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success of the operation with a message.
     */
    public function changeOrderStatus(Request $request)
    {
        // dd($request->all());
        $order = Order::findOrFail($request->id);
        $order->order_status_id = $request->status;
        $order->save();
        return response(['status' => 'success', 'message' => 'Updated Order Status Successfully']);
    }

    public function sendToSteadfast(Request $request, string $id)
    {
        $setting = SteadfastSetting::first();
        if (!$setting || (int) $setting->status !== 1) {
            return redirect()->back()->withErrors(['steadfast' => 'Steadfast is disabled or not configured.']);
        }

        $order = Order::findOrFail($id);
        [$ok, $message] = $this->sendOrderToSteadfast($order, $setting);
        if ($ok) {
            return redirect()->back()->with('success', $message);
        }
        return redirect()->back()->withErrors(['steadfast' => $message]);
    }

    public function bulkSendToSteadfast(Request $request)
    {
        $setting = SteadfastSetting::first();
        if (!$setting || (int) $setting->status !== 1) {
            return response()->json(['message' => 'Steadfast is disabled or not configured.'], 422);
        }

        $orderIds = $request->input('order_ids', []);
        if (!is_array($orderIds) || empty($orderIds)) {
            return response()->json(['message' => 'No orders selected.'], 422);
        }

        $orders = Order::whereIn('id', $orderIds)->get();
        $successCount = 0;
        $errors = [];

        foreach ($orders as $order) {
            [$ok, $message] = $this->sendOrderToSteadfast($order, $setting);
            if ($ok) {
                $successCount++;
            } else {
                $errors[] = "Order #{$order->id}: {$message}";
            }
        }

        return response()->json([
            'message' => "Sent {$successCount} order(s) to Steadfast.",
            'errors' => $errors,
        ]);
    }

    private function sendOrderToSteadfast(Order $order, SteadfastSetting $setting): array
    {
        if (!empty($order->courier_consignment_id)) {
            return [false, 'This order is already sent to Steadfast.'];
        }

        $address = is_string($order->order_address) ? json_decode($order->order_address, true) : (array) $order->order_address;
        $personal = is_string($order->personal_info) ? json_decode($order->personal_info, true) : (array) $order->personal_info;

        $recipientName = trim(($personal['first_name'] ?? '') . ' ' . ($personal['last_name'] ?? ''));
        if (!$recipientName) {
            $recipientName = $personal['name'] ?? '';
        }
        $recipientPhone = $personal['phone'] ?? ($address['phone'] ?? null);

        $addressParts = array_filter([
            $address['address'] ?? null,
            $address['city'] ?? null,
            $address['zip_code'] ?? ($address['zip'] ?? null),
            $address['state'] ?? null,
            $address['country'] ?? null,
        ]);
        $recipientAddress = implode(', ', $addressParts);

        if (!$recipientName || !$recipientPhone || !$recipientAddress) {
            return [false, 'Recipient name, phone or address is missing.'];
        }

        $codAmount = $order->payment_status ? 0 : (float) $order->amount;

        config([
            'steadfast-courier.base_url' => $setting->base_url ?: config('steadfast-courier.base_url'),
            'steadfast-courier.api_key' => $setting->api_key,
            'steadfast-courier.secret_key' => $setting->secret_key,
        ]);

        try {
            if (!class_exists(\SteadFast\SteadFastCourierLaravelPackage\SteadfastCourier::class)) {
                require_once base_path('vendor/steadfast-courier/steadfast-courier-laravel-package/src/SteadfastCourier.php');
            }
            $steadfast = new \SteadFast\SteadFastCourierLaravelPackage\SteadfastCourier();
            $response = $steadfast->placeOrder([
                'invoice' => (string) ($order->invoice_id ?? ('INV-' . $order->id)),
                'recipient_name' => $recipientName,
                'recipient_phone' => $recipientPhone,
                'recipient_address' => $recipientAddress,
                'cod_amount' => $codAmount,
                'note' => 'Order #' . $order->id,
            ]);
        } catch (\Throwable $e) {
            return [false, 'Steadfast API error: ' . $e->getMessage()];
        }

        if (($response['status'] ?? null) === 200 && isset($response['consignment'])) {
            $consignment = $response['consignment'];

            $order->update([
                'courier_provider' => 'steadfast',
                'courier_consignment_id' => $consignment['consignment_id'] ?? null,
                'courier_tracking_code' => $consignment['tracking_code'] ?? null,
                'courier_status' => $consignment['status'] ?? null,
                'courier_response' => $response,
                'courier_sent_at' => now(),
            ]);

            return [true, 'Order sent to Steadfast successfully.'];
        }

        $message = $response['message'] ?? 'Failed to send order to Steadfast.';
        return [false, $message];
    }
}
