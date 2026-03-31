<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRule extends Model
{
    protected $fillable = [
        'name',
        'type',
        'min_cost',
        'cost',
        'status',
    ];
}
