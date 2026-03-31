<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BkashSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class BkashSettingController extends Controller
{
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|integer',
            'account_mode' => 'required|integer',
            'app_key' => 'required|string',
            'app_secret' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        BkashSetting::updateOrCreate(
            ['id' => $id],
            [
                'status' => $request->status,
                'account_mode' => $request->account_mode,
                'app_key' => $request->app_key,
                'app_secret' => $request->app_secret,
                'username' => $request->username,
                'password' => $request->password,
            ]
        );

        Toastr::success('Settings Updated Successfully!');
        return redirect()->back()->with('active_tab', 'bkash');
    }
}
