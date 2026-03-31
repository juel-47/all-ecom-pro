<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\Slider;
use App\Models\LogoSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function sliders()
    {
        $sliders = Cache::remember('sliders', 3600, function () {
            return Slider::active()
                ->orderBy('serial', 'asc')
                ->select('id', 'banner', 'title', 'btn_url', 'type')
                ->get(['id', 'banner', 'title', 'btn_url', 'type']);
        });

        return response()->json(['sliders' => $sliders]);
    }

    public function brands()
    {
        $brands = Brand::active()
            ->featured()
            ->select('id', 'name', 'logo')
            ->get();

        return response()->json(['brands' => $brands]);
    }
    public function colors()
    {
        $colors = Color::select('id', 'color_name', 'color_code')->where('status', '1')->get();

        return response()->json([
            'data' => $colors
        ]);
    }

    public function sizes()
    {
        $sizes = Size::select('id', 'size_name')->where('status', '1')->get();

        return response()->json([
            'data' => $sizes]);
        }
    public function categories()
    {
        $categories = Category::active()
            ->select('id', 'name', 'image', 'slug')
            ->with([
                'subCategories' => function ($subQuery) {
                    $subQuery->active();
                    $subQuery->select('id', 'category_id', 'name', 'slug')->with([
                        'childCategories' => function ($childQuery) {
                            $childQuery->active();
                            $childQuery->select('id', 'sub_category_id', 'name', 'slug');
                        }
                    ]);
                }
            ])
            ->get();

        return response()->json(['categories' => $categories]);
    }

    public function homeProducts()
    {
        $homeProducts = Category::where(['front_show' => 1, 'status' => 1])
            ->select('id', 'name', 'slug', 'icon', 'image')
            ->orderBy('id', 'asc')
            ->with(['products' => function ($q) {
                $q->active()
                    ->select('id', 'name', 'slug', 'category_id', 'price', 'offer_price', 'img_alt_text', 'qty', 'thumb_image')
                    ->with(['productImageGalleries.color', 'colors:id,color_name,color_code,price,is_default', 'sizes:id,size_name,price,is_default'])
                    ->withreview()
                    ->orderBy('id', 'desc')
                    ->take(8); 
            }])
            ->get();

        return response()->json(['homeProducts' => $homeProducts]);
    }

    public function getTypeBaseProduct()
    {
        $typeBaseProducts = [];
        $typeBaseProducts['new_arrival'] = Product::withreview()->with(['category', 'colors', 'sizes', 'productImageGalleries'])->where(['product_type' => 'new_arrival', 'status' => 1, 'is_approved' => 1])->orderBy('id', 'desc')->get();
        $typeBaseProducts['featured_product'] = Product::withreview()->with(['category', 'colors', 'sizes', 'productImageGalleries'])->where(['product_type' => 'featured_product', 'status' => 1, 'is_approved' => 1])->orderBy('id', 'desc')->get();
        $typeBaseProducts['top_product'] = Product::withreview()->with(['category', 'colors', 'sizes', 'productImageGalleries'])->where(['product_type' => 'top_product', 'status' => 1, 'is_approved' => 1])->orderBy('id', 'desc')->get();
        $typeBaseProducts['best_product'] = Product::withreview()->with(['category', 'colors', 'sizes', 'productImageGalleries'])->where(['product_type' => 'best_product', 'status' => 1, 'is_approved' => 1])->orderBy('id', 'desc')->get();
        return $typeBaseProducts;
    }
    public function logos(){
        $logo=LogoSetting::select('id','logo', 'favicon', )->get();
        return response()->json(['logo'=>$logo]);
    }


    public function settings(){
        $settings=GeneralSetting::select('id', 'site_name', 'contact_email', 'contact_phone', 'contact_address', 'currency_name', 'currency_icon', 'time_zone', 'map')->first();
        return response()->json(['settings'=>$settings]);
    }

}