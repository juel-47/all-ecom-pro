<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class GeneralSetting extends Model
{
    protected $fillable = [
        'site_name',
        // 'layout',
        'contact_email',
        'contact_phone',
        'contact_address',
        'currency_name',
        'currency_icon',
        'time_zone',
        'map'
    ];

    protected static function booted()
    {
        static::saved(function ($setting) {
            Cache::forget('shared_settings');
        });

        static::deleted(function ($setting) {
            Cache::forget('shared_settings');
        });
    }
}
