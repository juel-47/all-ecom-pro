<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $guarded = [];

     public function user()
    {
        return $this->belongsTo(Customer::class);
    }
    // public function productReviewGallery()
    // {
    //     return $this->hasMany(ProductReviewGallery::class);
    // }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}