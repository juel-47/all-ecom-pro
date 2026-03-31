<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    protected $fillable = ['name','category_id','sub_category_id', 'slug', 'status'];
     public function category(){
        return $this->belongsTo(Category::class);
    }
    public function subCategory(){
        return $this->belongsTo(SubCategory::class);
    }

    /** scope */
    public function scopeActive($qury)
    {
        return $qury->where('status', 1);
    }

    protected static function booted()
    {
        static::saved(function ($childCategory) {
            \Illuminate\Support\Facades\Cache::forget('home_categories');
            \Illuminate\Support\Facades\Cache::forget('shared_categories_tree');
        });

        static::deleted(function ($childCategory) {
            \Illuminate\Support\Facades\Cache::forget('home_categories');
            \Illuminate\Support\Facades\Cache::forget('shared_categories_tree');
        });
    }
}
