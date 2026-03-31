<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'title',
        'type',
        'category_id',
        'product_id',
        'buy_quantity',
        'get_quantity',
        'start_date',
        'end_date',
        'allow_coupon_stack',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
