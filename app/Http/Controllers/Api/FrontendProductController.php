<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontendProductController extends Controller
{
    //seleted multiplue category wise
    private function applyFilters($query, Request $request)
    {

        if ($request->filled('category_ids') && !$request->filled('subcategory_ids') && !$request->filled('childcategory_ids')) {
            $query->whereIn('category_id', (array) $request->category_ids);
        }


        if ($request->filled('subcategory_ids') && !$request->filled('childcategory_ids')) {
            $query->whereIn('subcategory_id', (array) $request->subcategory_ids);
        }


        if ($request->filled('childcategory_ids')) {
            $query->whereIn('childcategory_id', (array) $request->childcategory_ids);
        }


        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('slug', 'like', "%{$keyword}%");
            });
        }


        if ($request->filled('brand_ids')) {
            $query->whereIn('brand_id', (array) $request->brand_ids);
        }


        // if ($request->filled('color_ids')) {
        //     $query->whereHas('colors', fn($q) => $q->whereIn('colors.id', (array)$request->color_ids));
        // }
        if ($request->filled('color_ids')) {
            $colorIds = (array) $request->color_ids; // ensure array

            $query->whereHas('productImageGalleries', function ($q) use ($colorIds) {
                $q->whereIn('color_id', $colorIds)
                    ->whereNotNull('image');
            });
        }


        if ($request->filled('size_ids')) {
            $query->whereHas('sizes', fn($q) => $q->whereIn('sizes.id', (array)$request->size_ids));
        }




        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }


        if ($request->filled('min_stock') && $request->filled('max_stock')) {
            $query->whereBetween('stock', [$request->min_stock, $request->max_stock]);
        } elseif ($request->filled('min_stock')) {
            $query->where('stock', '>=', $request->min_stock);
        } elseif ($request->filled('max_stock')) {
            $query->where('stock', '<=', $request->max_stock);
        }


        // Sorting and special filters
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'lowtohigh':
                    $query->orderBy('price', 'asc');
                    break;
                case 'hightolow':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'featureproduct':
                    $query->where('product_type', 'featured_product')
                        ->orderBy('created_at', 'desc');
                    break;
                case 'recommended':
                    $query->withAvg('reviews', 'rating')
                        ->orderBy('reviews_avg_rating', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        }

        return $query;
    }



    /**
     * All Products (Shop Page)
     */
    public function allProducts(Request $request)
    {
        $query = Product::active();
        if (!$request->has('sort_by')) {
            $query->orderBy('id', 'desc');
        }
        $query = $this->applyFilters($query, $request);

        $products = $query->with([
            'category',
            'colors',
            'sizes',
            'productImageGalleries:id,image,product_id,color_id',
            'productImageGalleries.color:id,color_name,color_code'
        ])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate(25);
        // dd($products);

        return response()->json(['status' => true, 'products' => $products]);
    }

    /**
     * Category Products
     */
    public function categoryProducts($slug, Request $request)
    {
        $category = Category::active()->where('slug', $slug)->first();
        if (!$category) return response()->json(['status' => false, 'message' => 'Category not found'], 404);

        $query = Product::active()->where('category_id', $category->id);
        $query = $this->applyFilters($query, $request);

        $products = $query->with(['category', 'colors', 'sizes'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate(24);

        return response()->json([
            'status' => true,
            'category' => $category->only(['id', 'name', 'slug']),
            'products' => $products
        ]);
    }

    /**
     * Subcategory Products
     */
    public function subcategoryProducts($slug, Request $request)
    {
        $subcategory = SubCategory::active()->where('slug', $slug)->first();
        if (!$subcategory) return response()->json(['status' => false, 'message' => 'Subcategory not found'], 404);

        $query = Product::active()->where('sub_category_id', $subcategory->id);
        $query = $this->applyFilters($query, $request);

        $products = $query->with(['category', 'colors', 'sizes'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate(24);

        return response()->json([
            'status' => true,
            'subcategory' => $subcategory->only(['id', 'name', 'slug']),
            'products' => $products
        ]);
    }

    /**
     * Childcategory Products
     */
    public function childcategoryProducts($slug, Request $request)
    {
        $childcategory = ChildCategory::active()->where('slug', $slug)->first();
        if (!$childcategory) return response()->json(['status' => false, 'message' => 'Childcategory not found'], 404);

        $query = Product::active()->where('child_category_id', $childcategory->id);
        $query = $this->applyFilters($query, $request);

        $products = $query->with(['category', 'colors', 'sizes'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate(24);

        return response()->json([
            'status' => true,
            'childcategory' => $childcategory->only(['id', 'name', 'slug']),
            'products' => $products
        ]);
    }

    /**
     * Product Details
     */
    public function productDetails(string $slug)
    {
        $product = Product::with([
            'category:id,name,slug,image,icon',
            'productImageGalleries:id,image,product_id,color_id',
            'productImageGalleries.color:id,color_name,color_code',
            // 'colors' => fn($q) => $q->select('colors.id as color_id', 'colors.color_name', 'colors.color_code', 'colors.price', 'colors.is_default')->withPivot('product_id', 'id'),
            'sizes' => fn($q) => $q->select('sizes.id as size_id', 'sizes.size_name', 'sizes.price', 'sizes.is_default')->withPivot('product_id', 'id'),
            'brand:id,name,slug,logo'
        ])->where('slug', $slug)->active()->first();

        if (!$product) return response()->json(['status' => false, 'message' => 'Product not found'], 404);

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

        return response()->json(['product' => $product, 'reviews' => $reviews]);
    }

    /**
     * Search Products (Keyword + Filter + Sort)
     */
    public function productSearch(Request $request)
    {
        $query = Product::active();
        $query = $this->applyFilters($query, $request);

        $products = $query->with(['category', 'colors', 'sizes'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate(20);

        return response()->json($products);
    }
}