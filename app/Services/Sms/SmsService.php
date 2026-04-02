<?php

namespace App\Services\Sms;

use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\SmsSetting;
use Illuminate\Support\Facades\Log;
use Larament\Barta\Facades\Barta;

class SmsService
{
    public function sendOrderConfirmation(Order $order): bool
    {
        $setting = SmsSetting::first();
        if (!$setting || (int) $setting->status !== 1) {
            return false;
        }

        $phone = $this->getOrderPhone($order);
        if (!$phone) {
            return false;
        }

        $message = $this->buildMessage($order, $setting->message_template);

        if ($this->isBangladeshNumber($phone)) {
            if ((int) $setting->bd_enabled === 1 && $setting->bd_driver) {
                if ($this->sendViaBarta($phone, $message, $setting)) {
                    return true;
                }
            }

            if ((int) $setting->global_enabled === 1) {
                return $this->sendViaGlobal($phone, $message, $setting);
            }

            return false;
        }

        if ((int) $setting->global_enabled === 1) {
            return $this->sendViaGlobal($phone, $message, $setting);
        }

        return false;
    }

    private function sendViaBarta(string $phone, string $message, SmsSetting $setting): bool
    {
        try {
            $driver = $setting->bd_driver;
            $credentials = $setting->bd_credentials ?? [];

            if (!$driver) {
                return false;
            }

            config([
                'barta.default' => $driver,
                "barta.drivers.{$driver}" => $credentials,
            ]);

            Barta::driver($driver)
                ->to($phone)
                ->message($message)
                ->send();

            return true;
        } catch (\Throwable $e) {
            Log::error('BD SMS failed', [
                'error' => $e->getMessage(),
                'driver' => $setting->bd_driver,
                'phone' => $phone,
            ]);
            return false;
        }
    }

    private function sendViaGlobal(string $phone, string $message, SmsSetting $setting): bool
    {
        if (empty($setting->endpoint_url) || empty($setting->to_key) || empty($setting->message_key)) {
            return false;
        }

        $others = $setting->extra_params ?? [];
        $headers = $setting->headers ?? [];

        if ($setting->sender_key && $setting->sender_value) {
            $others[$setting->sender_key] = $setting->sender_value;
        }

        if ($setting->auth_type && $setting->auth_type !== 'none') {
            $this->applyAuth($setting, $headers, $others);
        }

        $gateway = [
            'method' => $setting->http_method ?: 'POST',
            'url' => $setting->endpoint_url,
            'params' => [
                'send_to_param_name' => $setting->to_key,
                'msg_param_name' => $setting->message_key,
                'others' => $others,
            ],
            'headers' => $headers,
            'add_code' => (bool) $setting->add_code,
        ];

        if ($setting->content_type === 'json') {
            $gateway['json'] = true;
            $gateway['jsonToArray'] = (bool) $setting->json_to_array;
        }

        if ($setting->wrapper) {
            $gateway['wrapper'] = $setting->wrapper;
            if (!empty($setting->wrapper_params)) {
                $gateway['wrapperParams'] = $setting->wrapper_params;
            }
        }

        config([
            'sms-api.default' => 'dynamic',
            'sms-api.country_code' => $setting->country_code ?: config('sms-api.country_code', '1'),
            'sms-api.timeout' => $setting->request_timeout ?: config('sms-api.timeout', 30),
            'sms-api.dynamic' => $gateway,
        ]);

        try {
            $sms = smsapi()->gateway('dynamic');
            if ($setting->country_code) {
                $sms->countryCode($setting->country_code);
            }

            $result = $sms->sendMessage($phone, $message);
            return $result->isSuccessful();
        } catch (\Throwable $e) {
            Log::error('Global SMS failed', [
                'error' => $e->getMessage(),
                'phone' => $phone,
                'endpoint' => $setting->endpoint_url,
            ]);
            return false;
        }
    }

    private function applyAuth(SmsSetting $setting, array &$headers, array &$others): void
    {
        $authType = $setting->auth_type;
        $key = $setting->auth_key;
        $value = $setting->auth_value;

        if ($authType === 'bearer' && $value) {
            $headers['Authorization'] = 'Bearer ' . $value;
            return;
        }

        if ($authType === 'basic' && $key !== null && $value !== null) {
            $headers['Authorization'] = 'Basic ' . base64_encode($key . ':' . $value);
            return;
        }

        if ($authType === 'header' && $key) {
            $headers[$key] = (string) $value;
            return;
        }

        if ($authType === 'query' && $key) {
            $others[$key] = (string) $value;
        }
    }

    private function getOrderPhone(Order $order): ?string
    {
        $personal = is_string($order->personal_info)
            ? json_decode($order->personal_info, true)
            : (array) $order->personal_info;

        $address = is_string($order->order_address)
            ? json_decode($order->order_address, true)
            : (array) $order->order_address;

        $phone = $personal['phone'] ?? ($address['phone'] ?? null);

        if (!$phone && $order->customer) {
            $phone = $order->customer->phone ?? null;
        }

        if (!$phone && $order->customer_id) {
            $phone = $order->customer?->phone ?? null;
        }

        return $phone ? $this->sanitizePhone($phone) : null;
    }

    private function sanitizePhone(string $phone): string
    {
        return preg_replace('/[^\d\+]/', '', $phone) ?? $phone;
    }

    private function isBangladeshNumber(string $phone): bool
    {
        $phone = $this->sanitizePhone($phone);

        if (str_starts_with($phone, '+880')) {
            return true;
        }

        if (str_starts_with($phone, '880')) {
            return true;
        }

        return (bool) preg_match('/^01\d{9}$/', $phone);
    }

    private function buildMessage(Order $order, ?string $template): string
    {
        $general = GeneralSetting::first();
        $currency = $general?->currency_name ?? '';
        $siteName = $general?->site_name ?? '';

        $default = "Your order #{order_id} has been placed successfully. Amount: {amount} {currency}.";
        $message = $template ?: $default;

        $replacements = [
            '{order_id}' => (string) $order->id,
            '{invoice_id}' => (string) ($order->invoice_id ?? ''),
            '{amount}' => (string) ($order->amount ?? ''),
            '{currency}' => $currency,
            '{payment_method}' => (string) ($order->payment_method ?? ''),
            '{site_name}' => $siteName,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $message);
    }
}
