<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoneerSetting extends Model
{
     protected $fillable = [
        'api_key', 'api_secret_key', 'program_id', 'currency_name', 'country_name', 'account_mode', 'status', 'api_url', 'token_url'
    ];
}
