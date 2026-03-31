<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PayoneerSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class PayoneerSettingController extends Controller
{
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $request->validate([
            'status' => 'required|integer',
            'account_mode' => 'required|integer',
            'country_name' => 'required|max:200',
            'currency_name' => 'required|max:200',
            'api_key' => 'required',
            // 'secret_key' => 'required',
            'api_secret_key' => 'required',
            'program_id' => 'required',
            'api_url'=>'nullable|url',
            'token_url'=>'nullable|url'
        ]);

        PayoneerSetting::updateOrCreate(
            [
                'id' => $id
            ],
            [
                'status' => $request->status,
                'account_mode' => $request->account_mode,
                'country_name' => $request->country_name,
                'currency_name' => $request->currency_name,
                'api_key' => $request->api_key,
                'api_secret_key' => $request->api_secret_key,
                'program_id' => $request->program_id,
                'api_url'=>$request->api_url,
                'token_url'=>$request->token_url
                // 'currency_rate' => $request->currency_rate,
            ]
        );
        Toastr::success('Settings Updated Successfully!');
        return redirect()->back()->with('active_tab', 'payoneer');
    }
}
