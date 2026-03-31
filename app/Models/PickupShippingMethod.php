<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupShippingMethod extends Model
{
    protected $fillable = [
        'name',
        'store_name',
        'address',
        'map_location',
        'phone',
        'email',
        'cost',
        'status'
    ];
    protected $attributes = [
        'cost' => 0,
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'store_id');
    }
}
