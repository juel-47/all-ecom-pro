<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\OrderDataTable;
use App\DataTables\statusOrdersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
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
}
