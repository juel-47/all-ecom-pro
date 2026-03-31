<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FooterInfo extends Model
{
    protected $fillable = [
        'logo',
        'phone',
        'email',
        'address',
        'copyright'
    ];

    public function getLogoAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http') && !str_starts_with($value, 'storage/')) {
            return 'storage/' . $value;
        }
        return $value;
    }

    protected static function booted()
    {
        static::saved(function ($footerInfo) {
            Cache::forget('shared_footer_info');
        });

        static::deleted(function ($footerInfo) {
            Cache::forget('shared_footer_info');
        });
    }
}
