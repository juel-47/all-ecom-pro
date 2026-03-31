<?php

use App\Models\VippsSetting;

return [

    'active_setting' => function () {
        $setting = VippsSetting::firstWhere('active', true);

        if (!$setting) {
            throw new \Exception('MobilePay (Vipps) configuration not found.');
        }

        // Default URLs based on environment
        $defaultTokenUrl = $setting->environment === 'production'
            ? 'https://api.vipps.no/accessToken/get'
            : 'https://apitest.vipps.no/accessToken/get';

        $defaultCheckoutUrl = $setting->environment === 'production'
            ? 'https://api.vipps.no/ecomm/v2/payments'
            : 'https://apitest.vipps.no/ecomm/v2/payments';

        return [
            'environment' => $setting->environment,
            'client_id' => $setting->client_id,
            'client_secret' => $setting->client_secret,
            'subscription_key' => $setting->subscription_key,
            'merchant_serial_number' => $setting->merchant_serial_number,
            'webhook_secret' => $setting->webhook_secret,
            'token_url' => $setting->token_url ?: $defaultTokenUrl,  // Admin override possible
            'checkout_url' => $setting->checkout_url ?: $defaultCheckoutUrl, // Admin override possible
        ];
    },

];
