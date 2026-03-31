<?php

namespace App\Http\Middleware;

use App\Models\BkashSetting;
use Closure;
use Illuminate\Http\Request;

class LoadBkashConfig
{
    public function handle(Request $request, Closure $next)
    {
        $setting = BkashSetting::first();

        if ($setting) {
            config([
                'bkash.sandbox' => (int) $setting->account_mode === 0,
                'bkash.credentials.app_key' => $setting->app_key,
                'bkash.credentials.app_secret' => $setting->app_secret,
                'bkash.credentials.username' => $setting->username,
                'bkash.credentials.password' => $setting->password,
            ]);
        }

        return $next($request);
    }
}
