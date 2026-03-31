<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaypalSettingRequest;
use App\Models\PaypalSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class PaypalSettingController extends Controller
{
     public function update(PaypalSettingRequest $request, string $id)
    {
        // dd($request->all());

        PaypalSetting::updateOrCreate(
            [
                'id'=>$id
            ],
            [
                'status'=>$request->status,
                'account_mode'=>$request->account_mode,
                'country_name'=>$request->country_name,
                'currency_name'=>$request->currency_name,
                'client_id'=>$request->client_id,
                'secret_key'=>$request->secret_key,
                'currency_rate'=>$request->currency_rate,
            ]);
            Toastr::success('Settings Updated Successfully!');
            return redirect()->back()->with('active_tab', 'paypal');
    }
}
