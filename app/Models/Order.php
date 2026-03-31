<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'invoice_id',
        'customer_id',
        'sub_total',
        'amount',
        'currency_name',
        'currency_icon',
        'product_qty',
        'payment_method',
        'payment_status',
        'order_address',
        'shipping_method',
        'coupon',
        // 'order_status',
        'order_status_id',
        'personal_info',
    ];

    protected $casts = [
        'order_address' => 'object',
        'shipping_method' => 'object',
        'coupon' => 'object',
    ];

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
    public function store()
    {
        return $this->belongsTo(PickupShippingMethod::class, 'store_id');
    }
}
