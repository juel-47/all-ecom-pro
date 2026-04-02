<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    protected $fillable = [
        'status',
        'message_template',
        'bd_enabled',
        'bd_driver',
        'bd_credentials',
        'global_enabled',
        'provider_name',
        'endpoint_url',
        'http_method',
        'content_type',
        'add_code',
        'country_code',
        'json_to_array',
        'wrapper',
        'wrapper_params',
        'auth_type',
        'auth_key',
        'auth_value',
        'sender_key',
        'sender_value',
        'to_key',
        'message_key',
        'headers',
        'extra_params',
        'request_timeout',
    ];

    protected $casts = [
        'bd_credentials' => 'array',
        'wrapper_params' => 'array',
        'headers' => 'array',
        'extra_params' => 'array',
    ];
}
