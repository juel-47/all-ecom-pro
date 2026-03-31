<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    protected $fillable = [
        'icon',
        'name',
        'slug',
        'status',
        'front_show',
        'image',
        'meta_title',
        'meta_description',
    ];
    /** Elequent relationships */
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    /** scope */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function scopeFrontShow($query)
    {
        return $query->where('front_show', 1);
    }

    public function getImageAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http') && !str_starts_with($value, 'storage/')) {
            return 'storage/' . $value;
        }
        return $value;
    }

    public function getIconAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http') && !str_starts_with($value, 'storage/')) {
            return 'storage/' . $value;
        }
        return $value;
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    protected static function booted()
    {
        static::saved(function ($category) {
            Cache::forget('home_categories');
            Cache::forget('home_products');
            Cache::forget('home_type_base_products');
            Cache::forget('shared_categories_tree');
        });

        static::deleted(function ($category) {
            Cache::forget('home_categories');
            Cache::forget('home_products');
            Cache::forget('home_type_base_products');
            Cache::forget('shared_categories_tree');
        });
    }
}
