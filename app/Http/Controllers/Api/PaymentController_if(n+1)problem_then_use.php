<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PaypalSetting;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * COD Payment
     */
    public function payWithCod(Request $request)
    {
        $request->validate([
            'shipping_method' => 'required|array',
            'shipping_address' => 'required|array',
            'cart_items' => 'required|array|min:1',
            'coupon' => 'nullable|array'
        ]);

        $total = $this->calculateCartTotal($request->cart_items, $request->shipping_method, $request->coupon);

        $order = $this->storeOrder('COD', 0, Str::random(12), $total, $request);

        return response()->json([
            'status' => 'success',
            'message' => 'Order placed successfully!',
            'order_id' => $order->id
        ]);
    }

    /**
     * Paypal Payment
     */
    public function payWithPaypal(Request $request)
    {
        $request->validate([
            'shipping_method' => 'required|array',
            'shipping_address' => 'required|array',
            'cart_items' => 'required|array|min:1',
            'coupon' => 'nullable|array'
        ]);

        $paypalSetting = PaypalSetting::first();
        $config = $this->paypalConfig($paypalSetting);

        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        $total = $this->calculateCartTotal($request->cart_items, $request->shipping_method, $request->coupon);
        $paypalAmount = round($total * $paypalSetting->currency_rate, 2);

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('api.v1.paypal-success'),
                "cancel_url" => route('api.v1.paypal-cancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => $paypalSetting->currency_name,
                        "value" => $paypalAmount
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return response()->json(['status' => 'success', 'redirect_url' => $link['href']]);
                }
            }
        }

        return response()->json(['status' => 'error', 'message' => 'Paypal payment failed!'], 400);
    }

    /**
     * Paypal Success Callback
     */
    public function paypalSuccess(Request $request)
    {
        $paypalSetting = PaypalSetting::first();
        $config = $this->paypalConfig($paypalSetting);
        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->token);

        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            $total = $request->total_amount ?? 0;
            $order = $this->storeOrder('Paypal', 1, $response['id'], $total, $request);

            return response()->json(['status' => 'success', 'order_id' => $order->id]);
        }

        return response()->json(['status' => 'error', 'message' => 'Paypal payment failed!'], 400);
    }

    /**
     * Store Order & Transaction
     */
    private function storeOrder($paymentMethod, $paymentStatus, $transactionId, $totalAmount, $request)
    {
        $general = GeneralSetting::first();

        $order = Order::create([
            'invoice_id' => rand(100000, 999999),
            'customer_id' => Auth::id(),
            'sub_total' => $totalAmount,
            'amount' => $totalAmount,
            'currency_name' => $general->currency_name,
            'currency_icon' => $general->currency_icon,
            'product_qty' => count($request->cart_items),
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'order_address' => json_encode($request->shipping_address),
            'shipping_method' => json_encode($request->shipping_method),
            'coupon' => json_encode($request->coupon ?? []),
            'order_status' => 'pending'
        ]);

        $cartItems = collect($request->cart_items);
        $appliedCoupon = $request->coupon ?? null;

        // Preload all products to avoid N+1
        $productIds = $cartItems->pluck('product_id')->unique()->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        // Store Order Products
        foreach ($cartItems as $item) {
            $product = $products[$item['product_id']];
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'variants' => json_encode($item['options'] ?? []),
                'variants_total' => $item['variant_total'] ?? 0,
                'unit_price' => $item['price'],
                'qty' => $item['quantity']
            ]);

            // update stock
            $product->qty -= $item['quantity'];
            $product->save();
        }

        // Apply promotions
        $promotions = Promotion::where('status', 1)->get();
        foreach ($promotions as $promo) {
            if ($appliedCoupon && !$promo->allow_coupon_stack) continue;

            $qty = $promo->product_id
                ? $cartItems->where('product_id', $promo->product_id)->sum('quantity')
                : ($promo->category_id
                    ? $cartItems->filter(fn($i) => $products[$i['product_id']]->category_id == $promo->category_id)->sum('quantity')
                    : $cartItems->sum('quantity'));

            if ($qty >= $promo->buy_quantity) {
                if ($promo->type === 'free_product' && $promo->product_id) {
                    $freeProduct = $products[$promo->product_id] ?? Product::find($promo->product_id);
                    if ($freeProduct) {
                        OrderProduct::create([
                            'order_id' => $order->id,
                            'product_id' => $freeProduct->id,
                            'product_name' => $freeProduct->name,
                            'unit_price' => 0,
                            'qty' => $promo->get_quantity ?? 1,
                            'variants' => null,
                            'variants_total' => 0
                        ]);
                    }
                } elseif ($promo->type === 'free_shipping') {
                    $order->shipping_method = json_encode(['method' => 'Free Shipping', 'cost' => 0]);
                    $order->save();
                }
            }
        }

        // Store transaction
        Transaction::create([
            'order_id' => $order->id,
            'transaction_id' => $transactionId,
            'payment_method' => $paymentMethod,
            'amount' => $totalAmount,
            'amount_real_currency' => $totalAmount,
            'amount_real_currency_name' => $general->currency_name
        ]);
        
        Cart::where('user_id', auth('sanctum')->id())->delete();

        return $order;
    }

    /**
     * Calculate cart total including shipping, coupon & promotions
     */
    private function calculateCartTotal($cartItems, $shippingMethod, $coupon = null)
    {
        $cartCollection = collect($cartItems);

        // Subtotal with variants
        $subTotal = $cartCollection->sum(fn($item) => ($item['price'] + ($item['variant_total'] ?? 0)) * $item['quantity']);

        // Coupon discount
        $discount = 0;
        if ($coupon) {
            $discount = ($coupon['type'] === 'amount') ? $coupon['discount'] : $subTotal * $coupon['discount'] / 100;
        }

        // Shipping Fee
        $shippingFee = $shippingMethod['cost'] ?? 0;

        // Apply free shipping promotions
        $productIds = $cartCollection->pluck('product_id')->unique()->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $promotions = Promotion::where('status', 1)->get();
        foreach ($promotions as $promo) {
            if ($coupon && !$promo->allow_coupon_stack) continue;

            $qty = $promo->product_id
                ? $cartCollection->where('product_id', $promo->product_id)->sum('quantity')
                : ($promo->category_id
                    ? $cartCollection->filter(fn($i) => $products[$i['product_id']]->category_id == $promo->category_id)->sum('quantity')
                    : $cartCollection->sum('quantity'));

            if ($qty >= $promo->buy_quantity && $promo->type === 'free_shipping') {
                $shippingFee = 0;
            }
        }

        return max(0, $subTotal - $discount + $shippingFee);
    }

    /**
     * Paypal Config
     */
    private function paypalConfig($paypalSetting)
    {
        return [
            'mode' => $paypalSetting->account_mode === 1 ? 'live' : 'sandbox',
            'sandbox' => [
                'client_id' => $paypalSetting->client_id,
                'client_secret' => $paypalSetting->secret_key,
            ],
            'live' => [
                'client_id' => $paypalSetting->client_id,
                'client_secret' => $paypalSetting->secret_key,
            ],
            'payment_action' => 'Sale',
            'currency' => $paypalSetting->currency_name,
            'notify_url' => '',
            'locale' => 'en_US',
            'validate_ssl' => true,
        ];
    }
}
