<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PickupShippingMethod;
use App\Models\ShippingRule;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;

class CheckOutController extends Controller
{
    public function index()
    {
          $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $shippingMethods = ShippingRule::where('status', 1)->get(['id', 'name', 'type', 'cost']);
        $countryList = config('settings.country_list', []);
        $pickupMethods = PickupShippingMethod::where('status', 1)->get();
         $customerAddresses = CustomerAddress::where('customer_id', $user->id)->get();
        $customer_billing_address=CustomerAddress::where('customer_id', 'customer')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Checkout data loaded successfully',
            'countries' => $countryList,
            'shipping_methods' => $shippingMethods,
            'pickup_methods' => $pickupMethods,
            'customerAddresses'=>$customerAddresses,
        ]);
    }
}