<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmedMail;
use App\Models\BkashSetting;
use App\Models\Cart;
use App\Models\CodSetting;
use App\Models\GeneralSetting;
use App\Models\MobilePayTransaction;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PayoneerSetting;
use App\Models\PaypalSetting;
use App\Models\PickupShippingMethod;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\ShippingRule;
use App\Models\Transaction;
use App\Models\VippsSetting;
use Ihasan\Bkash\Facades\Bkash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use illuminate\Support\Str;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'payment_method' => 'required|in:cashOnDelivery,paypal,payoneer,mobilePay,bkash',
            ]);

            $response = match ($request->payment_method) {
                'cashOnDelivery' => $this->payWithCod($request),
                'paypal' => $this->payWithPaypal($request),
                'payoneer' => $this->payWithPayoneer($request),
                'mobilePay' => $this->payWithMobilePay($request),
                'bkash' => $this->payWithBkash($request),
            };

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);

                if (isset($data['redirect_url'])) {
                    return Inertia::location($data['redirect_url']);
                }

                if (($data['status'] ?? null) === 'success') {
                    return redirect()->route('order.success', ['order_id' => $data['order_id'] ?? null]);
                }

                return redirect()->back()->withErrors([
                    'payment' => $data['message'] ?? 'Payment failed',
                ]);
            }

            return $response;
        } catch (\Throwable $e) {
            Log::error('Checkout Store Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withErrors(['payment' => 'Server error: ' . $e->getMessage()]);
        }
    }

    /**
     * Safe options decoder
     */
    private function decodeOptions($options)
    {
        if (is_string($options)) {
            return json_decode($options, true) ?: [];
        }
        if (is_array($options)) {
            return $options;
        }
        if (is_object($options)) {
            return json_decode(json_encode($options), true);
        }
        return [];
    }

    /**
     * Cash On Delivery (COD) Payment
     */
    public function payWithCod(Request $request)
    {
        $codSetting = CodSetting::first();
        if (!$codSetting || $codSetting->status == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Currently, Cash on Delivery payment option is unavailable. Please choose an alternative payment method.'
            ], 403);
        }

        $cartItems = $this->getCartItems();
        $request->merge(['cart_items' => $cartItems]);
        if (empty($cartItems)) {
            return response()->json(['status' => 'error', 'message' => 'Cart is empty'], 400);
        }

        // Shipping method handle (pickup / dynamic)
        $shippingData = $request->shipping_method ?? null;
        $shippingMethod = null;
        $isPickup = false;

        if (isset($shippingData['id'])) {
            $pickup = PickupShippingMethod::find($shippingData['id']);
            if ($pickup) {
                $isPickup = true;
                $shippingMethod = [
                    'id' => $pickup->id,
                    'name' => $pickup->name,
                    'store_name' => $pickup->store_name,
                    'type' => 'pickup',
                    'cost' => (float) $pickup->cost ?? 0,
                    'address' => $pickup->address,
                    'phone' => $pickup->phone,
                    'email' => $pickup->email,
                    'map_location' => $pickup->map_location,
                ];
            } else {
                $rule = ShippingRule::findOrFail($shippingData['id']);
                $shippingMethod = [
                    'id' => $rule->id,
                    'name' => $rule->name,
                    'type' => $rule->type,
                    'cost' => (float) $rule->cost,
                ];
            }
        }

        // Validation
        if ($isPickup) {
            $request->validate([
                'shipping_method' => 'required|array',
                'store_id' => 'nullable|integer',
                'shipping_address' => 'nullable|array',
                'personal_info' => 'nullable|array',
                'coupon' => 'nullable|array'
            ]);
        } else {
            $request->validate([
                'shipping_method' => 'required|array',
                'shipping_address' => 'nullable|array',
                'personal_info' => 'nullable|array',
                'coupon' => 'nullable|array'
            ]);
        }

        $personalInfo = $request->personal_info ?? [];

        $total = $this->calculateCartTotal($cartItems, $shippingMethod, $request->coupon ?? null);

        // Store order
        $order = $this->storeOrder('COD', 0, Str::random(12), $total, $request, $shippingMethod, $isPickup, $personalInfo);

        return response()->json([
            'status' => 'success',
            'message' => 'Order placed successfully!',
            'order_id' => $order->id,
            'redirect_url' => route('order.success', ['order_id' => $order->id]),
            'shipping_method' => $shippingMethod
        ]);
    }

    /**
     * Paypal Payment
     */
    public function payWithPaypal(Request $request)
    {
        $cartItems = $this->getCartItems();
        $request->merge(['cart_items' => $cartItems]);

        $request->validate([
            'shipping_method' => 'required|array',
            'shipping_address' => 'nullable|array',
            'personal_info' => 'nullable|array',
            'coupon' => 'nullable|array'
        ]);

        $paypalSetting = PaypalSetting::first();
        if (!$paypalSetting || $paypalSetting->status == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Currently, Paypal payment option is unavailable. Please choose an alternative payment method.'
            ], 403);
        }

        $config = $this->paypalConfig($paypalSetting);
        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        $total = $this->calculateCartTotal($cartItems, $request->shipping_method, $request->coupon);
        $paypalAmount = round($total * $paypalSetting->currency_rate, 2);

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancel')
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
     * bKash Payment
     */
    public function payWithBkash(Request $request)
    {
        $bkashSetting = BkashSetting::first();
        if (!$bkashSetting || (int) $bkashSetting->status !== 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Currently, bKash payment option is unavailable. Please choose an alternative payment method.'
            ], 403);
        }

        config([
            'bkash.sandbox' => (int) $bkashSetting->account_mode === 0,
            'bkash.credentials.app_key' => $bkashSetting->app_key,
            'bkash.credentials.app_secret' => $bkashSetting->app_secret,
            'bkash.credentials.username' => $bkashSetting->username,
            'bkash.credentials.password' => $bkashSetting->password,
        ]);

        $cartItems = $this->getCartItems();
        $request->merge(['cart_items' => $cartItems]);

        if (empty($cartItems)) {
            return response()->json(['status' => 'error', 'message' => 'Cart is empty'], 400);
        }

        // Shipping method handle (pickup / dynamic)
        $shippingData = $request->shipping_method ?? null;
        $shippingMethod = null;
        $isPickup = false;

        if (isset($shippingData['id'])) {
            $pickup = PickupShippingMethod::find($shippingData['id']);
            if ($pickup) {
                $isPickup = true;
                $shippingMethod = [
                    'id' => $pickup->id,
                    'name' => $pickup->name,
                    'store_name' => $pickup->store_name,
                    'type' => 'pickup',
                    'cost' => (float) $pickup->cost ?? 0,
                    'address' => $pickup->address,
                    'phone' => $pickup->phone,
                    'email' => $pickup->email,
                    'map_location' => $pickup->map_location,
                ];
            } else {
                $rule = ShippingRule::findOrFail($shippingData['id']);
                $shippingMethod = [
                    'id' => $rule->id,
                    'name' => $rule->name,
                    'type' => $rule->type,
                    'cost' => (float) $rule->cost,
                ];
            }
        }

        // Validation
        if ($isPickup) {
            $request->validate([
                'shipping_method' => 'required|array',
                'store_id' => 'nullable|integer',
                'shipping_address' => 'nullable|array',
                'personal_info' => 'nullable|array',
                'coupon' => 'nullable|array'
            ]);
        } else {
            $request->validate([
                'shipping_method' => 'required|array',
                'shipping_address' => 'nullable|array',
                'personal_info' => 'nullable|array',
                'coupon' => 'nullable|array'
            ]);
        }

        $personalInfo = $request->personal_info ?? [];
        $total = $this->calculateCartTotal($cartItems, $shippingMethod, $request->coupon ?? null);
        $merchantInvoiceNumber = 'BK-' . strtoupper(Str::random(10));

        $request->session()->put('bkash.checkout', [
            'customer_id' => Auth::guard('customer')->id(),
            'shipping_method' => $shippingMethod,
            'is_pickup' => $isPickup,
            'shipping_address' => $request->shipping_address ?? [],
            'personal_info' => $personalInfo,
            'coupon' => $request->coupon ?? null,
            'cart_items' => $cartItems,
            'total' => $total,
            'merchant_invoice_number' => $merchantInvoiceNumber,
        ]);

        try {
            $response = Bkash::createPayment([
                'amount' => $total,
                'callback_url' => route('bkash.callback'),
                'merchant_invoice_number' => $merchantInvoiceNumber,
                'payer_reference' => $personalInfo['phone'] ?? null,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => 'Bkash payment initiation failed: ' . $e->getMessage()], 400);
        }

        if (isset($response['paymentID'])) {
            $request->session()->put('bkash.checkout.payment_id', $response['paymentID']);
        }

        $redirectUrl = $response['bkashURL'] ?? $response['paymentURL'] ?? $response['redirectURL'] ?? $response['url'] ?? null;
        if (!$redirectUrl) {
            return response()->json([
                'status' => 'error',
                'message' => $response['statusMessage'] ?? 'Bkash payment initiation failed.'
            ], 400);
        }

        return response()->json(['status' => 'success', 'redirect_url' => $redirectUrl]);
    }

    public function paypalSuccess(Request $request)
    {
        $paypalSetting = PaypalSetting::first();
        $config = $this->paypalConfig($paypalSetting);
        $provider = new PayPalClient($config);
        $provider->getAccessToken();
        $personalInfo = $request->personal_info ?? [];

        $response = $provider->capturePaymentOrder($request->token);

        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            $cartItems = $this->getCartItems();
            $request->merge(['cart_items' => $cartItems]);

            $total = $this->calculateCartTotal($cartItems, $request->shipping_method, $request->coupon ?? null);
            $order = $this->storeOrder('Paypal', 1, $response['id'], $total, $request, null, false, $personalInfo);
            return redirect()->route('order.success', ['order_id' => $order->id]);
        }

        return response()->json(['status' => 'error', 'message' => 'Paypal payment failed!'], 400);
    }

    public function paypalCancel(Request $request)
    {
        return response()->json([
            'status' => 'cancelled',
            'message' => 'Payment was cancelled by the user.'
        ], 200);
    }

    /**
     * Payoneer Payment
     */
    public function payWithPayoneer(Request $request)
    {
        $payoneerSetting = PayoneerSetting::first();
        if (!$payoneerSetting || $payoneerSetting->status == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Currently, Payoneer payment option is unavailable. Please choose an alternative payment method.'
            ], 403);
        }

        $cartItems = $this->getCartItems();
        $request->merge(['cart_items' => $cartItems]);

        $request->validate([
            'shipping_method' => 'required|array',
            'shipping_address' => 'nullable|array',
            'personal_info' => 'nullable|array',
            'coupon' => 'nullable|array'
        ]);

        $config = payoneer_config();
        $total = $this->calculateCartTotal($cartItems, $request->shipping_method, $request->coupon);
        $referenceId = 'PO-' . strtoupper(Str::random(10));
        $amount = number_format($total, 2, '.', '');

        try {
            $tokenResponse = Http::asForm()->post($config['token_url'], [
                'grant_type' => 'client_credentials',
                'client_id' => $config['api_key'],
                'client_secret' => $config['api_secret_key'],
            ]);

            if (!$tokenResponse->successful()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to generate Access Token.',
                    'details' => $tokenResponse->body()
                ], 400);
            }

            $accessToken = $tokenResponse->json()['access_token'];

            $paymentResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post("{$config['api_url']}/programs/{$config['program_id']}/payouts", [
                'paymentId' => $referenceId,
                'amount' => ['value' => $amount, 'currency' => $config['currency']],
                'payer' => ['country' => $config['country'], 'reference' => Auth::id()],
                'description' => 'Product Purchase - ' . $referenceId,
                'redirect_urls' => ['return_url' => $config['return_url'], 'cancel_url' => $config['cancel_url']],
            ]);

            $data = $paymentResponse->json();

            if ($paymentResponse->successful() && isset($data['redirect_url'])) {
                return response()->json(['status' => 'success', 'redirect_url' => $data['redirect_url']]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $data['error_description'] ?? 'Payoneer payment initiation failed.',
                    'details' => $data
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Payoneer API error: ' . $e->getMessage()], 500);
        }
    }

    public function payoneerSuccess(Request $request)
    {
        $transactionId = $request->input('paymentId');
        $status = $request->input('status');

        if ($status !== 'SUCCESS') {
            return response()->json(['status' => 'error', 'message' => 'Payoneer payment not completed!'], 400);
        }
        $personalInfo = $request->personal_info ?? [];

        $cartItems = $this->getCartItems();
        $request->merge(['cart_items' => $cartItems]);
        $total = $this->calculateCartTotal($cartItems, $request->shipping_method, $request->coupon ?? null);
        $order = $this->storeOrder('Payoneer', 1, $transactionId, $total, $request, null, false, $personalInfo);

        return redirect()->route('order.success', ['order_id' => $order->id]);
    }

    public function payoneerCancel()
    {
        return response()->json(['status' => 'cancelled', 'message' => 'Payment cancelled by user.']);
    }

    /**
     * bKash Success Redirect
     */
    public function bkashSuccess(Request $request)
    {
        $payment = $request->session()->get('payment');
        $checkout = $request->session()->get('bkash.checkout');

        if (!$payment || !$checkout) {
            return redirect()->route('checkout')->withErrors(['payment' => 'Bkash session expired. Please try again.']);
        }

        if (!Auth::guard('customer')->check() && !empty($checkout['customer_id'])) {
            Auth::guard('customer')->loginUsingId($checkout['customer_id']);
        }

        Auth::shouldUse('customer');

        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')->with('error', 'Please login to complete payment.');
        }

        $trxId = $payment['trxID'] ?? $payment['trxId'] ?? $payment['trx_id'] ?? null;
        $transactionStatus = strtolower((string) ($payment['transactionStatus'] ?? ''));

        if (!$trxId && $transactionStatus && !in_array($transactionStatus, ['completed', 'success'], true)) {
            return redirect()->route('checkout')->withErrors(['payment' => 'Bkash payment was not completed.']);
        }

        $request->merge([
            'cart_items' => $checkout['cart_items'] ?? [],
            'shipping_address' => $checkout['shipping_address'] ?? [],
            'personal_info' => $checkout['personal_info'] ?? [],
            'coupon' => $checkout['coupon'] ?? null,
        ]);

        $transactionId = $trxId
            ?? ($payment['paymentID'] ?? $checkout['payment_id'] ?? Str::random(12));

        $order = $this->storeOrder(
            'Bkash',
            1,
            $transactionId,
            $checkout['total'] ?? 0,
            $request,
            $checkout['shipping_method'] ?? null,
            $checkout['is_pickup'] ?? false,
            $checkout['personal_info'] ?? []
        );

        $request->session()->forget('bkash.checkout');

        return redirect()->route('order.success', ['order_id' => $order->id]);
    }

    /**
     * bKash Failed Redirect
     */
    public function bkashFailed(Request $request)
    {
        $request->session()->forget('bkash.checkout');
        return redirect()->route('checkout')->withErrors(['payment' => 'Bkash payment failed or cancelled.']);
    }

    /**
     * MobilePay / Vipps
     */
    public function payWithMobilePay(Request $request)
    {
        $mobilePaySetting = VippsSetting::first();
        if (!$mobilePaySetting || $mobilePaySetting->active == 0) {
            return response()->json(['status' => 'error', 'message' => 'Currently, MobilePay payment option is unavailable. Please choose an alternative payment method.'], 403);
        }

        $config = $this->getConfig();
        $cartItems = $this->getCartItems();
        $request->merge(['cart_items' => $cartItems]);

        $request->validate([
            'shipping_method' => 'required|array',
            'shipping_address' => 'nullable|array',
            'personal_info' => 'nullable|array',
            'coupon' => 'nullable|array'
        ]);

        try {
            $token = $this->getToken($config);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

        $total = $this->calculateCartTotal($cartItems, $request->shipping_method, $request->coupon);
        $amount = round($total * 100);
        $orderId = 'INV-' . time();

        $payload = [
            "merchantInfo" => ["merchantSerialNumber" => $config['merchant_serial_number']],
            "customerInfo" => ["mobileNumber" => $request->user()['phone'] ?? '4520000000'],
            "transaction" => ["orderId" => $orderId, "amount" => $amount, "transactionText" => "Order Payment"],
            "urls" => ["returnUrl" => route('mobilepay.success'), "cancelUrl" => route('mobilepay.cancel')]
        ];

        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
            'Ocp-Apim-Subscription-Key' => $config['subscription_key'], // Fixed quote typo
            'Content-Type' => 'application/json'
        ])->post($config['checkout_url'], $payload);

        if ($response->failed()) {
            Log::error('Vipps checkout failed', $response->json());
            return response()->json(['status' => 'error', 'message' => 'Payment failed'], 400);
        }

        MobilePayTransaction::create(['order_id' => $orderId, 'amount' => $total, 'status' => 'pending', 'response' => $response->json()]);

        return response()->json(['status' => 'success', 'redirect_url' => $response->json()['url'] ?? null]);
    }

    public function mobilePaySuccess(Request $request)
    {
        $config = $this->getConfig();
        $orderId = $request->input('orderId');
        if (!$orderId) return response()->json(['status' => 'error', 'message' => 'Order ID missing'], 400);

        $token = $this->getToken($config);

        $statusResponse = Http::withHeaders([
            'Authorization' => "Bearer $token",
            'Ocp-Apim-Subscription-Key' => $config['subscription_key']
        ])->get($config['checkout_url'] . '/' . $orderId);

        if ($statusResponse->failed()) {
            Log::error('Vipps verification failed', $statusResponse->json());
            return response()->json(['status' => 'error', 'message' => 'Failed to verify payment'], 400);
        }

        $transaction = MobilePayTransaction::where('order_id', $orderId)->first();
        if ($transaction) {
            $transaction->update(['status' => $statusResponse->json()['status'] ?? 'completed', 'response' => $statusResponse->json()]);
        }
        if (($statusResponse->json()['status'] ?? '') === 'SUCCESS') {
            $cartItems = $this->getCartItems();
            $personalInfo = $request->personal_info ?? [];
            $total = $this->calculateCartTotal($cartItems, $request->shipping_method ?? [], $request->coupon ?? null);
            $order = $this->storeOrder('MobilePay', 1, $orderId, $total, $request, $request->shipping_method ?? [], false, $personalInfo);

            return redirect()->route('order.success', ['order_id' => $order->id]);
        }

        return response()->json(['status' => 'success', 'message' => 'Payment verified', 'data' => $statusResponse->json()]);
    }

    public function mobilePayCancel(Request $request)
    {
        $orderId = $request->input('orderId');
        if ($orderId) {
            $transaction = MobilePayTransaction::where('order_id', $orderId)->first();
            if ($transaction) $transaction->update(['status' => 'cancelled']);
        }

        return response()->json(['status' => 'cancel', 'message' => 'Payment cancelled']);
    }

    public function webhook(Request $request)
    {
        $config = $this->getConfig();
        $payload = $request->all();
        $signature = $request->header('Vipps-Signature');

        $computedHash = hash_hmac('sha256', json_encode($payload), $config['webhook_secret']);
        if (!hash_equals($computedHash, $signature)) {
            Log::warning('Vipps webhook signature mismatch', ['payload' => $payload]);
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
        }

        $orderId = $payload['orderId'] ?? null;
        if ($orderId) {
            $transaction = MobilePayTransaction::where('order_id', $orderId)->first();
            if ($transaction) {
                $transaction->update(['status' => $payload['status'] ?? $transaction->status, 'response' => $payload]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Utility functions
     */
    private function storeOrder($paymentMethod, $paymentStatus, $transactionId, $totalAmount, $request, $shippingMethod = null, $isPickup = false, $personalInfo = null)
    {
        $general = GeneralSetting::first();

        $cartSubtotal = collect($request->cart_items)->sum(function ($item) {
            $options = $this->decodeOptions($item['options'] ?? []);
            return ($item['price'] + ($options['variant_total'] ?? 0) + ($options['extra_price'] ?? 0)) * $item['quantity'];
        });

        $order = Order::create([
            'invoice_id' => rand(100000, 999999),
            'customer_id' => Auth::id(),
            'sub_total' => $cartSubtotal,
            'amount' => $totalAmount,
            'currency_name' => $general->currency_name,
            'currency_icon' => $general->currency_icon,
            'product_qty' => count($request->cart_items),
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'order_address' => $isPickup ? null : json_encode($request->shipping_address),
            'shipping_method' => json_encode($shippingMethod ?? []),
            'personal_info' => json_encode($personalInfo),
            'coupon' => json_encode($request->coupon ?? []),
            'order_status' => 'pending'
        ]);

        foreach ($request->cart_items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $options = $this->decodeOptions($item['options'] ?? []);

            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'variants' => json_encode($options),
                'unit_price' => $item['price'],
                'extra_price' => $options['extra_price'] ?? 0,
                'front_image' => $options['font_image'] ?? null,
                'back_image' => $options['back_image'] ?? null,
                'variants_total' => $options['variant_total'] ?? 0,
                'qty' => $item['quantity'],
                'total_price' => ($item['price'] + ($options['variant_total'] ?? 0) + ($options['extra_price'] ?? 0)) * $item['quantity'],
            ]);

            $product->qty -= $item['quantity'];
            $product->save();
        }

        Transaction::create([
            'order_id' => $order->id,
            'transaction_id' => $transactionId,
            'payment_method' => $paymentMethod,
            'amount' => $totalAmount,
            'amount_real_currency' => $totalAmount,
            'amount_real_currency_name' => $general->currency_name
        ]);

        Cart::where('user_id', Auth::id())->delete();

        if (Auth::user() && Auth::user()->email) {
            Mail::to(Auth::user()->email)->send(new OrderConfirmedMail($order));
        }

        return $order;
    }

    private function getCartItems()
    {
        return Cart::where('user_id', Auth::id())->get()->map(function ($item) {
            $options = $item->options;
            if (is_string($options)) {
                $options = json_decode($options, true) ?: [];
            } elseif (!is_array($options)) {
                $options = [];
            }

            return [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'options' => $options
            ];
        })->toArray();
    }

    private function calculateCartTotal($cartItems, $shippingMethod, $coupon = null)
    {
        $cartCollection = collect($cartItems);

        $subTotal = $cartCollection->sum(function ($item) {
            $options = $this->decodeOptions($item['options'] ?? []);
            return ($item['price'] + ($options['variant_total'] ?? 0) + ($options['extra_price'] ?? 0)) * $item['quantity'];
        });

        $discount = 0;
        if ($coupon) {
            $discount = ($coupon['type'] === 'amount') ? $coupon['discount'] : $subTotal * $coupon['discount'] / 100;
        }

        $shippingFee = $shippingMethod['cost'] ?? 0;

        $promotions = Promotion::where('status', 1)->get();
        foreach ($promotions as $promo) {
            if ($coupon && !$promo->allow_coupon_stack) continue;
            $qty = $promo->product_id
                ? $cartCollection->where('product_id', $promo->product_id)->sum('quantity')
                : ($promo->category_id
                    ? $cartCollection->filter(fn($i) => Product::find($i['product_id'])->category_id == $promo->category_id)->sum('quantity')
                    : $cartCollection->sum('quantity'));

            if ($qty >= $promo->buy_quantity && $promo->type === 'free_shipping') {
                $shippingFee = 0;
            }
        }

        return max(0, $subTotal - $discount + $shippingFee);
    }

    private function paypalConfig($paypalSetting)
    {
        return [
            'mode' => $paypalSetting->account_mode === 1 ? 'live' : 'sandbox',
            'sandbox' => ['client_id' => $paypalSetting->client_id, 'client_secret' => $paypalSetting->secret_key],
            'live' => ['client_id' => $paypalSetting->client_id, 'client_secret' => $paypalSetting->secret_key],
            'payment_action' => 'Sale',
            'currency' => $paypalSetting->currency_name,
            'notify_url' => '',
            'locale' => 'en_US',
            'validate_ssl' => true,
        ];
    }

    private function getConfig()
    {
        $vipps = VippsSetting::first();
        return [
            'merchant_serial_number' => $vipps->merchant_serial_number,
            'subscription_key' => $vipps->subscription_key,
            'checkout_url' => $vipps->checkout_url,
            'webhook_secret' => $vipps->webhook_secret,
        ];
    }

    private function getToken($config)
    {
        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $config['subscription_key'],
            'Content-Type' => 'application/json'
        ])->post($config['checkout_url'] . '/accessToken', [
            'merchantId' => $config['merchant_serial_number'],
        ]);

        if ($response->failed()) throw new \Exception('Failed to get MobilePay token');
        return $response->json()['accessToken'] ?? null;
    }
}
