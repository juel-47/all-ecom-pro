<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BkashSetting;
use App\Models\CodSetting;
use App\Models\CustomerAddress;
use App\Models\PayoneerSetting;
use App\Models\PaypalSetting;
use App\Models\PickupShippingMethod;
use App\Models\ShippingRule;
use App\Models\VippsSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CheckOutController extends Controller
{
    public function index()
    {
        $user = Auth::guard('customer')->user();

        if (!$user) {
            return redirect()->route('customer.login')->with('error', 'Please login to checkout.');
        }

        $shippingMethods = ShippingRule::where('status', 1)
            ->select('id', 'name', 'type', 'cost')
            ->get();

        $pickupMethods = PickupShippingMethod::where('status', 1)->get();

        $countryList = config('settings.country_list', []);

        $customerAddresses = CustomerAddress::where('customer_id', $user->id)->get();

        $paypalSetting = PaypalSetting::first();
        $payoneerSetting = PayoneerSetting::first();
        $mobilePaySetting = VippsSetting::first();
        $codSetting = CodSetting::first();
        $bkashSetting = BkashSetting::first();

        $paymentMethods = [
            [
                'key' => 'paypal',
                'label' => 'PayPal',
                'description' => 'Pay securely with PayPal',
                'enabled' => (int)($paypalSetting?->status ?? 0) === 1,
            ],
            [
                'key' => 'bkash',
                'label' => 'bKash',
                'description' => 'Pay securely with bKash',
                'enabled' => (int)($bkashSetting?->status ?? 0) === 1,
            ],
            [
                'key' => 'mobilePay',
                'label' => 'MobilePay',
                'description' => 'Pay instantly via app',
                'enabled' => (int)($mobilePaySetting?->active ?? 0) === 1,
            ],
            [
                'key' => 'payoneer',
                'label' => 'Payoneer',
                'description' => 'Pay with Payoneer',
                'enabled' => (int)($payoneerSetting?->status ?? 0) === 1,
            ],
            [
                'key' => 'cashOnDelivery',
                'label' => 'Cash On Delivery',
                'description' => 'Pay when you receive the order',
                'enabled' => (int)($codSetting?->status ?? 0) === 1,
            ],
        ];

        return Inertia::render('CheckoutPage', [
            'shipping_methods' => $shippingMethods,
            'pickup_methods'   => $pickupMethods,
            'countries'        => $countryList,
            'customer_addresses' => $customerAddresses,
            'payment_methods' => $paymentMethods,
        ]);
    }

    public function success($order_id = null)
    {
        return Inertia::render('OrderSuccessPage', [
            'order_id' => $order_id
        ]);
    }
}
