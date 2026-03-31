<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CodeSettingRquest;
use App\Models\CodSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class CodSettingController extends Controller
{
    public function update(CodeSettingRquest $request, string $id)
    {
         // dd($request->all());

        CodSetting::updateOrCreate(
            [
                'id'=>$id
            ],
            [
                'status'=>$request->status,
            ]);
            Toastr::success('Settings Updated Successfully!');
            return redirect()->back()->with('active_tab', 'cod');
    }
}
