<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailConfigurationUpdateRequest;
use App\Http\Requests\GeneralSettingUpdateRequest;
use App\Http\Requests\LogoSettingUpdateRequest;
use App\Models\EmailConfiguration;
use App\Models\GeneralSetting;
use App\Models\LogoSetting;
use App\Traits\ImageUploadTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    use ImageUploadTrait;
    public function index()
    {
        $generalSetting = GeneralSetting::first();
        $emailConfiguration = EmailConfiguration::first();
        $logoSetting = LogoSetting::first();
        return view('backend.settings.index', compact('generalSetting', 'emailConfiguration', 'logoSetting'));
    }
    public function generalSettingUpdate(GeneralSettingUpdateRequest $request)
    {
        GeneralSetting::updateOrCreate(['id' => 1], [
            'site_name' => $request->site_name,
            // 'layout'=>$request->layout,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'contact_address' => $request->contact_address,
            'currency_name' => $request->currency_name,
            'currency_icon' => $request->currency_icon,
            'time_zone' => $request->timezone,
            'map' => $request->map
        ]);
        Cache::forget('general_setting');
        Toastr::success('Settings Updated Successfully', 'success');
        return redirect()->back();
    }

    /** email configuration setting update */
    public function emailConfigurationUpdate(EmailConfigurationUpdateRequest $request)
    {
        EmailConfiguration::updateOrCreate(
            ['id' => 1],
            [
                'email' => $request->email,
                'host' => $request->mail_host,
                'username' => $request->smtp_username,
                'password' => $request->smtp_password,
                'port' => $request->smtp_port,
                'encryption' => $request->email_encryption
            ]
        );
        Toastr::success('Settings Updated Successfully', 'success');
        return redirect()->back();
    }

    public function logSettingUpdate(LogoSettingUpdateRequest $request)
    {
        $logoPath = $this->uploadSpecialImage($request, 'logo', 'uploads/logo', $request->old_logo);
        $favicon = $this->uploadSpecialImage($request, 'favicon', 'uploads/logo', $request->old_favicon);
        LogoSetting::updateOrCreate([
            'id' => 1
        ], [
            'logo' => !empty($logoPath) ? $logoPath : $request->old_logo,
            'favicon' => !empty($favicon) ? $favicon : $request->old_favicon,
        ]);
        Toastr::success('Logo setting Updated Successfully!', 'success');
        return redirect()->back();
    }
    
}