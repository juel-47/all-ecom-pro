<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LogoSetting extends Model
{
    protected $fillable = [
        'logo',
        'favicon'
    ];
    public function getLogoAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http') && !str_starts_with($value, 'storage/')) {
            return 'storage/' . $value;
        }
        return $value;
    }

    public function getFaviconAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http') && !str_starts_with($value, 'storage/')) {
            return 'storage/' . $value;
        }
        return $value;
    }

    protected static function booted()
    {
        static::saved(function ($logoSetting) {
            Cache::forget('shared_logos');
        });

        static::deleted(function ($logoSetting) {
            Cache::forget('shared_logos');
        });
    }
}
