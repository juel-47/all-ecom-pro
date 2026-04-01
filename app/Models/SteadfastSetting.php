<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SteadfastSetting extends Model
{
    protected $fillable = [
        'status',
        'base_url',
        'api_key',
        'secret_key',
        'webhook_bearer_token',
    ];
}
