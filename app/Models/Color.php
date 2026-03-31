<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $guarded = [];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_colors');
    }
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    
}
