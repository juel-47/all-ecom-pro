<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobilePayTransaction extends Model
{
    protected $fillable = ['order_id', 'status', 'amount', 'response'];

    protected $casts = [
        'response' => 'array'
    ];
}
