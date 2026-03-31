<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\VippsSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class MobilePaySettingController extends Controller
{
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'active' => 'nullable|boolean',
            'environment' => 'nullable|in:test,production',
            'client_id' => 'nullable|string',
            'client_secret' => 'nullable|string',
            'subscription_key' => 'nullable|string',
            'merchant_serial_number' => 'nullable|string',
            'webhook_secret' => 'nullable|string',
            'token_url' => 'nullable|string',
            'checkout_url' => 'nullable|string',
        ]);

        VippsSetting::updateOrCreate(['id' => $id], $data);

        Toastr::success('Vipps credentials updated!');
        return redirect()->back()->with('active_tab', 'mobile-pay');
    }
}
