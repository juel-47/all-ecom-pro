<?php

use App\Models\PayoneerSetting;

// return [
//     'settings' => function () {
//         $setting = PayoneerSetting::first();
//         if (!$setting) {
//             throw new \Exception('Payoneer credentials not configured!');
//         }

//         return [
//             'mode' => $setting->account_mode === 1 ? 'live' : 'sandbox',
//             'api_key' => $setting->api_key,
//             'api_secret_key' => $setting->api_secret_key,
//             'program_id' => $setting->program_id,
//             'currency' => $setting->currency_name,
//             'country' => $setting->country_name,
//             'status' => $setting->status,
//             'api_url' => $setting->api_url,
//             'return_url' => route('api.v1.payoneer-success'),
//             'cancel_url' => route('api.v1.payoneer-cancel'),
//         ];
//     }
// ];

return [
    'settings' => function () {
        $setting = PayoneerSetting::first();
        if (!$setting) {
            throw new \Exception('Payoneer credentials not configured!');
        }

        // Admin set করা API/Token URL
        $apiBaseUrl = $setting->api_url ?? ($setting->account_mode === 1
            ? 'https://api.payoneer.com/v4'
            : 'https://api.sandbox.payoneer.com/v4');

        $tokenUrl = $setting->token_url ?? ($setting->account_mode === 1
            ? 'https://api.payoneer.com/PartnerAPI/oauth2/token'
            : 'https://api.sandbox.payoneer.com/PartnerAPI/oauth2/token');

        return [
            'mode' => $setting->account_mode === 1 ? 'live' : 'sandbox',
            'api_key' => $setting->api_key,
            'api_secret_key' => $setting->api_secret_key,
            'program_id' => $setting->program_id,
            'currency' => $setting->currency_name,
            'country' => $setting->country_name,
            'status' => $setting->status,
            'api_url' => $apiBaseUrl,     // dynamic
            'token_url' => $tokenUrl,     // dynamic
            'return_url' => route('api.v1.payoneer-success'),
            'cancel_url' => route('api.v1.payoneer-cancel'),
        ];
    }
];
