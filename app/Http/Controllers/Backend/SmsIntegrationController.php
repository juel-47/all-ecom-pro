<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SmsSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SmsIntegrationController extends Controller
{
    public function index()
    {
        $smsSetting = SmsSetting::first();
        return view('backend.integrations.sms', compact('smsSetting'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|integer',
            'message_template' => 'nullable|string',
            'bd_enabled' => 'required|integer',
            'bd_driver' => 'nullable|string',
            'bd_credentials' => 'nullable|string',
            'bd_key_1' => 'nullable|string',
            'bd_value_1' => 'nullable|string',
            'bd_key_2' => 'nullable|string',
            'bd_value_2' => 'nullable|string',
            'bd_key_3' => 'nullable|string',
            'bd_value_3' => 'nullable|string',
            'global_enabled' => 'required|integer',
            'provider_name' => 'nullable|string',
            'endpoint_url' => 'nullable|string',
            'http_method' => 'required|string',
            'content_type' => 'required|string',
            'add_code' => 'required|integer',
            'country_code' => 'nullable|string',
            'json_to_array' => 'required|integer',
            'wrapper' => 'nullable|string',
            'wrapper_params' => 'nullable|string',
            'auth_type' => 'required|string',
            'auth_key' => 'nullable|string',
            'auth_value' => 'nullable|string',
            'sender_key' => 'nullable|string',
            'sender_value' => 'nullable|string',
            'to_key' => 'nullable|string',
            'message_key' => 'nullable|string',
            'headers' => 'nullable|string',
            'extra_params' => 'nullable|string',
            'request_timeout' => 'required|integer',
        ]);

        try {
            $bdCredentials = $this->buildKeyValueCredentials($request);
            if (!$bdCredentials) {
                $bdCredentials = $this->decodeJson($request->bd_credentials);
            }
            $headers = $this->decodeJson($request->headers);
            $extraParams = $this->decodeJson($request->extra_params);
            $wrapperParams = $this->decodeJson($request->wrapper_params);
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }

        SmsSetting::updateOrCreate(
            ['id' => $id],
            [
                'status' => (int) $request->status,
                'message_template' => $request->message_template,
                'bd_enabled' => (int) $request->bd_enabled,
                'bd_driver' => $request->bd_driver,
                'bd_credentials' => $bdCredentials,
                'global_enabled' => (int) $request->global_enabled,
                'provider_name' => $request->provider_name,
                'endpoint_url' => $request->endpoint_url,
                'http_method' => strtoupper($request->http_method),
                'content_type' => $request->content_type,
                'add_code' => (int) $request->add_code,
                'country_code' => $request->country_code,
                'json_to_array' => (int) $request->json_to_array,
                'wrapper' => $request->wrapper,
                'wrapper_params' => $wrapperParams,
                'auth_type' => $request->auth_type,
                'auth_key' => $request->auth_key,
                'auth_value' => $request->auth_value,
                'sender_key' => $request->sender_key,
                'sender_value' => $request->sender_value,
                'to_key' => $request->to_key,
                'message_key' => $request->message_key,
                'headers' => $headers,
                'extra_params' => $extraParams,
                'request_timeout' => (int) $request->request_timeout,
            ]
        );

        Toastr::success('SMS settings updated successfully!');
        return redirect()->back();
    }

    private function decodeJson(?string $value): ?array
    {
        if (!$value) {
            return null;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('JSON fields must be valid JSON.');
        }

        return $decoded;
    }

    private function buildKeyValueCredentials(Request $request): ?array
    {
        $creds = [];

        for ($i = 1; $i <= 3; $i++) {
            $key = trim((string) $request->input("bd_key_{$i}", ''));
            $value = $request->input("bd_value_{$i}");
            if ($key !== '') {
                $creds[$key] = $value;
            }
        }

        return $creds ?: null;
    }
}
