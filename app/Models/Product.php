<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    protected $guarded = [];
    protected $appends = ['effective_price', 'active_campaign_product'];

    // public function vendor()
    // {
    //     return $this->belongsTo(vendor::class);
    // }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'sub_category_id');
    }

    public function childcategory()
    {
        return $this->belongsTo(ChildCategory::class, 'child_category_id');
    }

    public function productImageGalleries()
    {
        return $this->hasMany(ProductImageGallery::class);
    }
    // public function variants()
    // {
    //     return $this->hasMany(ProductVariant::class);
    // }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    // public function reviews()
    // {
    //     return $this->hasMany(ProductReview::class);
    // }
    // Product has many ProductColors
    // Many-to-Many relationship with Size
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes')->withPivot('size_price');
    }

    // Many-to-Many relationship with Color
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors')->withPivot('color_price');
    }


    /** review relationship */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /** 
     * who is create the product
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    /** scope  */

    public function scopeActive($query)
    {
        return $query->where(['status' => 1, 'is_approved' => 1]);
    }

    public function getThumbImageAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http') && !str_starts_with($value, 'storage/')) {
            return 'storage/' . $value;
        }
        return $value;
    }
    public function scopeWithReview($query)
    {
        return $query
            ->withAvg(['reviews' => fn($q) => $q->where('status', 1)], 'rating')
            ->withCount(['reviews' => fn($q) => $q->where('status', 1)])
            ->with([
                'category',
                'colors:id,color_name,color_code,price,is_default',
                'sizes:id,size_name,price,is_default',
            ]);
    }
    /** Shipping Method  relationship */
    public function shippingMethods()
    {
        return $this->belongsToMany(ShippingMethod::class, 'product_shipping')->withPivot('charge');
    }

    public function campaignProducts()
    {
        return $this->hasMany(CampaignProduct::class);
    }

    /**
     * Get the active campaign product for this product if any.
     */
    public function getActiveCampaignProductAttribute()
    {
        $now = now();

        // Check if relation is loaded to avoid N+1
        if ($this->relationLoaded('campaignProducts')) {
            return $this->campaignProducts->first(function ($item) use ($now) {
                // If campaign relation is also loaded, verify it
                if ($item->relationLoaded('campaign') && $item->campaign) {
                    return $item->campaign->status == 1 &&
                           $item->campaign->start_date <= $now &&
                           $item->campaign->end_date >= $now;
                }
                // If campaign not loaded but filtered in query (like in HomeController), assume valid
                return true; 
            });
        }

        return $this->campaignProducts()
            ->whereHas('campaign', function($query) use ($now) {
                $query->where('status', 1)
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
            })
            ->first();
    }

    /**
     * Get the effective price considering active campaigns.
     */
    public function getEffectivePriceAttribute()
    {
        $campaignProduct = $this->active_campaign_product;
        
        if ($campaignProduct) {
            if ($campaignProduct->discount_type === 'percentage') {
                return $this->price - ($this->price * $campaignProduct->discount_value / 100);
            } else {
                return $this->price - $campaignProduct->discount_value;
            }
        }

        // Fallback to offer price if exists and no campaign
        if ($this->offer_price > 0 && $this->offer_start_date <= date('Y-m-d') && $this->offer_end_date >= date('Y-m-d')) {
            return $this->offer_price;
        }

        return $this->price;
    }

    protected static function booted()
    {
        static::saved(function ($product) {
            Cache::forget('home_products');
            Cache::forget('home_type_base_products');
        });

        static::deleted(function ($product) {
            Cache::forget('home_products');
            Cache::forget('home_type_base_products');
        });
    }
}