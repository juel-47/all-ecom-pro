<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Slider extends Model
{
    protected $fillable = [
        'title',
        'type',
        'starting_price',
        'btn_url',
        'serial',
        'status',
        'banner'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getBannerAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http') && !str_starts_with($value, 'storage/')) {
            return 'storage/' . $value;
        }
        return $value;
    }
    protected static function booted()
    {
        static::saved(function ($slider) {
            Cache::forget('home_sliders');
        });

        static::deleted(function ($slider) {
            Cache::forget('home_sliders');
        });
    }
}
