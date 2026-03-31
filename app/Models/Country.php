<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name', 'code', 'status'];

    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function shippingCharges()
    {
        return $this->hasMany(ShippingCharge::class);
    }
}
