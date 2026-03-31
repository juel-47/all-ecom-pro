<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = ['country_id', 'name', 'status'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function shippingCharges()
    {
        return $this->hasMany(ShippingCharge::class);
    }
}
