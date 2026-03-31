<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkashSetting extends Model
{
    protected $fillable = [
        'status',
        'account_mode',
        'app_key',
        'app_secret',
        'username',
        'password',
    ];
}
