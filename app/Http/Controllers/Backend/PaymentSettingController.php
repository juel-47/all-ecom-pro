<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BkashSetting;
use App\Models\CodSetting;
use App\Models\PayoneerSetting;
use App\Models\PaypalSetting;
use App\Models\VippsSetting;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    public function index()
    {
         $paypalSetting=PaypalSetting::first();
        // $stripeSetting=StripeSetting::first();
        $codSetting=CodSetting::first();
        $payoneerSetting=PayoneerSetting::first();
        $mobilePaySetting=VippsSetting::first();
        $bkashSetting = BkashSetting::first();
        return view('backend.payment-setting.index', compact('paypalSetting', 'codSetting', 'payoneerSetting', 'mobilePaySetting', 'bkashSetting'));
    }
}
