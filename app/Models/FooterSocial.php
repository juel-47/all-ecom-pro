<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FooterSocial extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::saved(function ($footerSocial) {
            Cache::forget('shared_footer_social');
        });

        static::deleted(function ($footerSocial) {
            Cache::forget('shared_footer_social');
        });
    }
}
