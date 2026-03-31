<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        // 'vendor_id',
        'product_name',
        'variants',
        'variants_total',
        'unit_price',
        'qty',
        'extra_price',
        'front_image',
        'back_image',
    ];
    
    protected $casts = [
        'variants' => 'object',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
