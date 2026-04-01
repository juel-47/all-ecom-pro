<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SteadfastSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SteadfastSettingController extends Controller
{
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|integer',
            'base_url' => 'required|url',
            'api_key' => 'required|string',
            'secret_key' => 'required|string',
            'webhook_bearer_token' => 'nullable|string',
        ]);

        SteadfastSetting::updateOrCreate(
            ['id' => $id],
            [
                'status' => $request->status,
                'base_url' => $request->base_url,
                'api_key' => $request->api_key,
                'secret_key' => $request->secret_key,
                'webhook_bearer_token' => $request->webhook_bearer_token,
            ]
        );

        Toastr::success('Settings Updated Successfully!');
        return redirect()->back()->with('active_tab', 'steadfast');
    }
}
