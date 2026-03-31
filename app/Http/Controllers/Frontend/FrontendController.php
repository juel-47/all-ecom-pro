<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

// class FrontendController extends Controller
// {
//     //
// }

class FrontendController extends Controller
{
    // Filter Logic
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('category_ids') && !$request->filled('subcategory_ids') && !$request->filled('childcategory_ids')) {
            $query->whereIn('category_id', (array) $request->category_ids);
        }

        if ($request->filled('subcategory_ids') && !$request->filled('childcategory_ids')) {
            $query->whereIn('sub_category_id', (array) $request->subcategory_ids);
        }

        if ($request->filled('childcategory_ids')) {
            $query->whereIn('child_category_id', (array) $request->childcategory_ids);
        }

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(fn($q) => $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('slug', 'like', "%{$keyword}%"));
        }

        if ($request->filled('brand_ids')) {
            $query->whereIn('brand_id', (array)$request->brand_ids);
        }

        if ($request->filled('color_ids')) {
            $colorIds = (array)$request->color_ids;
            $query->whereHas('productImageGalleries', fn($q) => $q->whereIn('color_id', $colorIds)->whereNotNull('image'));
        }

        if ($request->filled('size_ids')) {
            $query->whereHas('sizes', fn($q) => $q->whereIn('sizes.id', (array)$request->size_ids));
        }

        // if ($request->filled('min_price') && $request->filled('max_price')) {
        //     $query->whereBetween('price', [$request->min_price, $request->max_price]);
        // }
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $minPrice = $request->filled('min_price') ? (int)$request->min_price : 0;
            $maxPrice = $request->filled('max_price') ? (int)$request->max_price : 9999999;

            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        if ($request->filled('min_stock') || $request->filled('max_stock')) {
            $min = $request->min_stock ?? 0;
            $max = $request->max_stock ?? 999999;
            $query->whereBetween('stock', [$min, $max]);
        }

        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'lowtohigh':
                    $query->orderBy('price', 'asc');
                    break;
                case 'hightolow':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                    $query->orderBy('id', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('id', 'asc');
                    break;
                case 'featureproduct':
                    $query->where('product_type', 'featured_product')->orderBy('id', 'desc');
                    break;
                case 'recommended':
                    $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        }

        return $query;
    }

    public function allProducts(Request $request)
    {
        $query = Product::active()
            ->select([
                'id',
                'name',
                'slug',
                'price',
                'qty',
                'offer_price',
                'offer_start_date',
                'offer_end_date',
                'thumb_image',
                'category_id',
                'sub_category_id',
                'child_category_id'
            ])
            ->with(['campaignProducts' => function($cq) {
                $cq->with('campaign')
                   ->whereHas('campaign', function($ccq) {
                    $ccq->where('status', 1)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now());
                });
            }])
            ->whereHas('category', function ($q) {
                $q->where('status', 1);
            });

        $query = $this->applyFilters($query, $request);

        if (!$request->has('sort_by')) {
            $query->orderBy('id', 'desc');
        }

        $products = $query->with([
            'category:id,name,slug'
        ])
            ->withCount([
                'reviews' => fn($q) => $q->where('status', 1),
                'colors' => fn($q) => $q->active(),
                'sizes' => fn($q) => $q->active(),
                'productImageGalleries' => fn($q) => $q->whereNotNull('color_id'),
            ])
            ->withAvg(['reviews' => fn($q) => $q->where('status', 1)], 'rating')
            ->paginate(20)
            ->withQueryString();

        // Static filters (Cached for 1 hour)
        $categories = Cache::remember('all_products_categories', 3600, fn() => Category::active()->get(['id', 'name', 'slug']));
        $brands = Cache::remember('all_products_brands', 3600, fn() => Brand::where('status', 1)->get(['id', 'name']));
        $colors = Cache::remember('all_products_colors', 3600, fn() => Color::where('status', 1)->get(['id', 'color_name', 'color_code']));
        $sizes = Cache::remember('all_products_sizes', 3600, fn() => Size::where('status', 1)->get(['id', 'size_name']));

        return Inertia::render('ProductPage', [
            'products'   => $products,
            'filters'    => $request->all(),
            'categories' => $categories,
            'brands'     => $brands,
            'colors'     => $colors,
            'sizes'      => $sizes,
        ]);
    }

    // Category Products
    public function categoryProducts($slug, Request $request)
    {
        $category = Category::active()->where('slug', $slug)->firstOrFail();

        $query = Product::active()
            ->select([
                'id',
                'name',
                'slug',
                'price',
                'qty',
                'offer_price',
                'offer_start_date',
                'offer_end_date',
                'thumb_image',
                'category_id',
                'sub_category_id',
                'child_category_id'
            ])
            ->with(['campaignProducts' => function($cq) {
                $cq->with('campaign')
                   ->whereHas('campaign', function($ccq) {
                    $ccq->where('status', 1)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now());
                });
            }])
            ->where('category_id', $category->id)
            ->whereNull('sub_category_id');

        $query = $this->applyFilters($query, $request);

        if (!$request->has('sort_by')) {
            $query->orderBy('id', 'desc');
        }

        $products = $query->with([
            'category:id,name,slug'
        ])
            ->withCount([
                'reviews' => fn($q) => $q->where('status', 1),
                'colors' => fn($q) => $q->active(),
                'sizes' => fn($q) => $q->active(),
                'productImageGalleries' => fn($q) => $q->whereNotNull('color_id'),
            ])
            ->withAvg(['reviews' => fn($q) => $q->where('status', 1)], 'rating')
            ->paginate(20)
            ->withQueryString();

        // Static filters (Cached for 1 hour)
        $brands = Cache::remember('cat_brands_' . $category->id, 3600, fn() => Brand::where('status', 1)->get(['id', 'name']));
        $colors = Cache::remember('cat_colors', 3600, fn() => Color::where('status', 1)->get(['id', 'color_name', 'color_code']));
        $sizes = Cache::remember('cat_sizes', 3600, fn() => Size::where('status', 1)->get(['id', 'size_name']));

        return Inertia::render('CategoryPage', [
            'type'     => 'category',
            'category' => $category,
            'products' => $products,
            'filters'  => $request->all(),
            'brands'   => $brands,
            'colors'   => $colors,
            'sizes'    => $sizes,
        ]);
    }

    // Subcategory Products
    public function subcategoryProducts($slug, Request $request)
    {
        $subcategory = SubCategory::active()->where('slug', $slug)->firstOrFail();

        $query = Product::active()
            ->select([
                'id',
                'name',
                'slug',
                'price',
                'qty',
                'offer_price',
                'offer_start_date',
                'offer_end_date',
                'thumb_image',
                'category_id',
                'sub_category_id',
                'child_category_id'
            ])
            ->with(['campaignProducts' => function($cq) {
                $cq->with('campaign')
                   ->whereHas('campaign', function($ccq) {
                    $ccq->where('status', 1)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now());
                });
            }])
            ->where('sub_category_id', $subcategory->id)
            ->whereNull('child_category_id');

        $query = $this->applyFilters($query, $request);

        if (!$request->has('sort_by')) {
            $query->orderBy('id', 'desc');
        }

        $products = $query->with([
            'category:id,name,slug'
        ])
            ->withCount([
                'reviews' => fn($q) => $q->where('status', 1),
                'colors' => fn($q) => $q->active(),
                'sizes' => fn($q) => $q->active(),
                'productImageGalleries' => fn($q) => $q->whereNotNull('color_id'),
            ])
            ->withAvg(['reviews' => fn($q) => $q->where('status', 1)], 'rating')
            ->paginate(20)
            ->withQueryString();

        // Static filters (Cached for 1 hour)
        $brands = Cache::remember('subcat_brands_' . $subcategory->id, 3600, fn() => Brand::where('status', 1)->get(['id', 'name']));
        $colors = Cache::remember('cat_colors', 3600, fn() => Color::where('status', 1)->get(['id', 'color_name', 'color_code']));
        $sizes = Cache::remember('cat_sizes', 3600, fn() => Size::where('status', 1)->get(['id', 'size_name']));

        return Inertia::render('CategoryPage', [
            'type'     => 'subcategory',
            'category' => $subcategory,
            'products' => $products,
            'filters'  => $request->all(),
            'brands'   => $brands,
            'colors'   => $colors,
            'sizes'    => $sizes,
        ]);
    }

    // Childcategory Products
    public function childcategoryProducts($slug, Request $request)
    {
        $childcategory = ChildCategory::active()->where('slug', $slug)->firstOrFail();

        $query = Product::active()
            ->select([
                'id',
                'name',
                'slug',
                'price',
                'qty',
                'offer_price',
                'offer_start_date',
                'offer_end_date',
                'thumb_image',
                'category_id',
                'sub_category_id',
                'child_category_id'
            ])
            ->with(['campaignProducts' => function($cq) {
                $cq->with('campaign')
                   ->whereHas('campaign', function($ccq) {
                    $ccq->where('status', 1)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now());
                });
            }])
            ->where('child_category_id', $childcategory->id);

        $query = $this->applyFilters($query, $request);

        if (!$request->has('sort_by')) {
            $query->orderBy('id', 'desc');
        }

        $products = $query->with([
            'category:id,name,slug'
        ])
            ->withCount([
                'reviews' => fn($q) => $q->where('status', 1),
                'colors' => fn($q) => $q->active(),
                'sizes' => fn($q) => $q->active(),
                'productImageGalleries' => fn($q) => $q->whereNotNull('color_id'),
            ])
            ->withAvg(['reviews' => fn($q) => $q->where('status', 1)], 'rating')
            ->paginate(20)
            ->withQueryString();

        // Static filters (Cached for 1 hour)
        $brands = Cache::remember('childcat_brands_' . $childcategory->id, 3600, fn() => Brand::where('status', 1)->get(['id', 'name']));
        $colors = Cache::remember('cat_colors', 3600, fn() => Color::where('status', 1)->get(['id', 'color_name', 'color_code']));
        $sizes = Cache::remember('cat_sizes', 3600, fn() => Size::where('status', 1)->get(['id', 'size_name']));

        return Inertia::render('CategoryPage', [
            'type'          => 'childcategory',
            'category'      => $childcategory,
            'products'      => $products,
            'filters'       => $request->all(),
            'brands'        => $brands,
            'colors'        => $colors,
            'sizes'         => $sizes,
        ]);
    }

    // Product Details
    public function productDetails(string $slug)
    {
        $cacheKey = "product_details_" . $slug;

         $product = Cache::remember($cacheKey, 1800, function () use ($slug) {

            return Product::query()
                ->select([
                    'id',
                    'name',
                    'slug',
                    'price',
                    'offer_price',
                    'offer_start_date',
                    'offer_end_date',
                    'short_description',
                    'qty',
                    'category_id',
                    'thumb_image',
                    'long_description',
                ])
                ->active()
                ->where('slug', $slug)
                ->whereHas('category', function ($q) {
                    $q->where('status', 1);
                })
                ->with(['campaignProducts' => function($cq) {
                    $cq->with('campaign')
                       ->whereHas('campaign', function($ccq) {
                        $ccq->where('status', 1)
                            ->where('start_date', '<=', now())
                            ->where('end_date', '>=', now());
                    });
                }])

                /*RELATIONS (OPTIMIZED) */

                ->with([
                    // Category (ONLY name)
                    'category:id,name',

                    // Product Images + Color
                    'productImageGalleries',
                    'productImageGalleries.color',

                    // Sizes (ONLY name, no pivot)
                    'sizes',
                    'colors',
                ])

                /* review */
                // ->withCount(['reviews' => fn($q) => $q->where('status', 1)])
                ->withAvg(['reviews' => fn($q) => $q->where('status', 1)], 'rating')

                ->firstOrFail();
        });
        $reviews = ProductReview::with(['user:id,name,email,image'])
            ->where('product_id', $product->id)
            ->where('status', 1)
            ->get()
            ->map(fn($review) => [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->review,
                'created_at' => $review->created_at,
                'user' => [
                    'id' => $review->user->id ?? null,
                    'name' => $review->user->name ?? 'Anonymous',
                    'image' => $review->user->image ?? null
                ]
            ]);

        $relatedProducts = Product::query()
            ->active()
            ->select('id', 'name', 'slug', 'category_id', 'thumb_image', 'price', 'offer_price', 'offer_start_date', 'offer_end_date', 'qty')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(6)
            ->with(['category:id,name', 'productImageGalleries', 'campaignProducts' => function($cq) {
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
            ->withAvg(['reviews' => fn($q) => $q->where('status', 1)], 'rating')
            ->get();

        return Inertia::render('ProductDetails', [
            'product' => $product,
            'reviews' => $reviews,
            'relatedProducts' => $relatedProducts
        ]);
    }

    // Product Search
    public function productSearch(Request $request)
    {
        $query = Product::active()
            ->select([
                'id', 'name', 'slug', 'price', 'qty', 'offer_price', 'offer_start_date', 'offer_end_date', 'thumb_image', 'category_id'
            ])
            ->with(['campaignProducts' => function($cq) {
                $cq->with('campaign')
                   ->whereHas('campaign', function($ccq) {
                    $ccq->where('status', 1)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now());
                });
            }]);

        $query = $this->applyFilters($query, $request);

        $products = $query->with(['category:id,name,slug', 'campaignProducts' => function($cq) {
            $cq->with('campaign')
               ->whereHas('campaign', function($ccq) {
                $ccq->where('status', 1)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            });
        }])
            ->withCount([
                'reviews' => fn($q) => $q->where('status', 1),
                'colors' => fn($q) => $q->active(),
                'sizes' => fn($q) => $q->active(),
                'productImageGalleries' => fn($q) => $q->whereNotNull('color_id'),
            ])
            ->withAvg(['reviews' => fn($q) => $q->where('status', 1)], 'rating')
            ->paginate(20)
            ->withQueryString();

        $categories = Category::active()->get(['id', 'name', 'slug']);
        $brands = Brand::where('status', 1)->get(['id', 'name']);
        $colors = Color::where('status', 1)->get(['id', 'color_name', 'color_code']);
        $sizes = Size::where('status', 1)->get(['id', 'size_name']);

        return Inertia::render('SearchPage', [
            'products'   => $products,
            'filters'    => $request->all(),
            'categories' => $categories,
            'brands'     => $brands,
            'colors'     => $colors,
            'sizes'      => $sizes,
            'query'      => $request->q
        ]);
    }

    public function liveSearch(Request $request)
    {
        $keyword = $request->q;
        if (!$keyword) return response()->json([]);

        $products = Product::active()
            ->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('slug', 'like', "%{$keyword}%");
            })
            ->select('id', 'name', 'slug', 'price', 'offer_price', 'thumb_image')
            ->take(8)
            ->get();

        return response()->json($products);
    }
}
