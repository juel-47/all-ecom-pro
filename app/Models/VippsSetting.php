<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VippsSetting extends Model
{
    protected $fillable = [
        'active',
        'environment',
        'client_id',
        'client_secret',
        'subscription_key',
        'merchant_serial_number',
        'webhook_secret',
        'token_url',
        'checkout_url',
    ];
}
