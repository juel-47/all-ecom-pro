<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = ['name', 'type', 'status'];

    public function charges()
    {
        return $this->hasMany(ShippingCharge::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_shipping')->withPivot('charge');
    }
}
