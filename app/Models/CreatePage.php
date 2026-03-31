<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CreatePage extends Model
{
    protected $guarded = [];
    protected static function booted()
    {
        $refreshCache = function () {
            Cache::forget('footer_create_page');
            Cache::remember('footer_create_page', 3600, function () {
                return CreatePage::select('page_for', 'name', 'slug', 'title', 'description')
                    ->get()
                    ->map(function ($item) {
                        return $item->only(['page_for', 'name', 'slug', 'title', 'description']);
                    });
            });
        };

        static::created($refreshCache);
        static::updated($refreshCache);
        static::deleted($refreshCache);
    }
}
