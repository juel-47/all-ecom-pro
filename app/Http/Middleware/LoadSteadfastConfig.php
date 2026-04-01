<?php

namespace App\Http\Middleware;

use App\Models\SteadfastSetting;
use Closure;
use Illuminate\Http\Request;

class LoadSteadfastConfig
{
    public function handle(Request $request, Closure $next)
    {
        $setting = SteadfastSetting::first();

        if ($setting) {
            config([
                'steadfast-courier.base_url' => $setting->base_url ?: config('steadfast-courier.base_url'),
                'steadfast-courier.api_key' => $setting->api_key,
                'steadfast-courier.secret_key' => $setting->secret_key,
                'steadfast-courier.webhook_bearer_token' => $setting->webhook_bearer_token,
            ]);
        }

        return $next($request);
    }
}
