<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'phone',
        'country',
        'state',
        'city',
        'zip',
        'address'
    ];
}
