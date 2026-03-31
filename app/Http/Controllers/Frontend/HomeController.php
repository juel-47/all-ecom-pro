<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Cache::remember('home_sliders', 3600, function () {
            return Slider::active()
                ->orderBy('serial', 'asc')
                ->select('id', 'banner', 'title', 'btn_url', 'type')
                ->get();
        });

        $categories = Cache::remember('home_categories', 3600, function() {
            return $this->categories();
        });

        $homeProducts = Cache::remember('home_products', 3600, function() {
            return $this->homeProducts();
        });

        $typeBaseProducts = Cache::remember('home_type_base_products', 3600, function() {
            return $this->getTypeBaseProduct();
        });
        return Inertia::render('Home',  [
            'categories' => $categories,
            'sliders' => $sliders,
            'homeProducts' => $homeProducts,
            'typeBaseProducts' => $typeBaseProducts,
        ]);
    }

    private function categories()
    {
        $categories = Category::active()->FrontShow()
            ->select('id', 'name', 'image', 'slug')
            ->with([
                'subCategories:id,category_id,name,slug',
                'subCategories.childCategories:id,sub_category_id,name,slug'
            ])
            ->get();

        return $categories;
    }

     private function homeProducts()
    {
        return Category::active()->frontshow()
            ->select('id', 'name', 'slug')
            ->orderBy('id', 'asc')
            ->with(['products' => function ($q) {
                $q->active()
                    ->whereNull('sub_category_id')
                    ->select(
                        'id',
                        'name',
                        'slug',
                        'category_id',
                        'thumb_image',
                        'price',
                        'offer_price',
                        'offer_start_date',
                        'offer_end_date',
                        'qty'
                    )
                    ->with(['campaignProducts' => function($cq) {
                        $cq->with('campaign')
                           ->whereHas('campaign', function($ccq) {
                            $ccq->where('status', 1)
                                ->where('start_date', '<=', now())
                                ->where('end_date', '>=', now());
                        });
                    }])
                    ->withCount([
                        'colors' => fn($q) => $q->active(),
                        'sizes' => fn($q) => $q->active(),
                        'productImageGalleries' => fn($q) => $q->whereNotNull('color_id'),
                    ])

                    ->orderByDesc('id')
                    ->limit(12);
            }])
            ->get();
    }
    private function getTypeBaseProduct()
    {
        $types = ['new_arrival', 'featured_product', 'top_product', 'best_product'];
        $result = [];

        foreach ($types as $type) {
            $result[$type] = Product::active()
                ->where('product_type', $type)
                ->where('is_approved', 1)
                ->whereHas('category', function ($q) {
                    $q->where('status', 1);
                })
                ->select(
                    'id',
                    'name',
                    'slug',
                    'category_id',
                    'thumb_image',
                    'price',
                    'offer_price',
                    'offer_start_date',
                    'offer_end_date',
                    'qty'
                )
                ->with(['campaignProducts' => function($cq) {
                    $cq->with('campaign')
                       ->whereHas('campaign', function($ccq) {
                        $ccq->where('status', 1)
                            ->where('start_date', '<=', now())
                            ->where('end_date', '>=', now());
                    });
                }])
                ->withCount([
                    'colors' => fn($q) => $q->active(),
                    'sizes' => fn($q) => $q->active(),
                    'productImageGalleries' => fn($q) => $q->whereNotNull('color_id'),
                ])

                ->orderByDesc('id')
                ->limit(6)
                ->get();
        }

        return $result;
    }
}
