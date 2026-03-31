<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $guarded = [];


    public function scopeActive($query)
    {
        return $query->where('status', 1);        
    }
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }
}
