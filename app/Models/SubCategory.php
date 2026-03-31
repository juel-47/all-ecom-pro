<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'status'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function childCategories()
    {
        return $this->hasMany(ChildCategory::class);
    }
    /** scope */

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    protected static function booted()
    {
        static::saved(function ($subCategory) {
            \Illuminate\Support\Facades\Cache::forget('home_categories');
            \Illuminate\Support\Facades\Cache::forget('shared_categories_tree');
        });

        static::deleted(function ($subCategory) {
            \Illuminate\Support\Facades\Cache::forget('home_categories');
            \Illuminate\Support\Facades\Cache::forget('shared_categories_tree');
        });
    }
}
