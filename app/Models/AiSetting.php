<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSetting extends Model
{
    protected $fillable = [
        'status',
        'default_provider',
        'fallback_provider',
        'system_prompt',
        'temperature',
        'max_tokens',
        'request_timeout',
        'openai_api_key',
        'openai_model',
        'gemini_api_key',
        'gemini_model',
        'groq_api_key',
        'groq_model',
    ];

    protected $casts = [
        'status' => 'boolean',
        'temperature' => 'float',
        'max_tokens' => 'integer',
        'request_timeout' => 'integer',
    ];
}
