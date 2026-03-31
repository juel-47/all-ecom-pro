<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'title',
        'sub_title',
        'description',
        'image',
        'banner',
        'status',
        'start_date',
        'end_date',
    ];

    public function campaignProducts()
    {
        return $this->hasMany(CampaignProduct::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'campaign_products')
                    ->withPivot(['discount_type', 'discount_value'])
                    ->withTimestamps();
    }
}
