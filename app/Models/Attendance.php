<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'date', 'start_time', 'end_time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
        // 'date'       => 'date',
    ];

}
