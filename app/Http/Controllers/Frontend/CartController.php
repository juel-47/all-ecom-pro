<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\customerCustomization;
use App\Models\Coupon;
use App\Models\Promotion;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\DB;


//update new cart controller

class CartController extends Controller
{
    use ImageUploadTrait;
    /* =========================
        ADD TO CART
    ========================= */
    public function addToCart(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'qty' => 'required|integer|min:1',
            'size_id' => 'nullable|integer|exists:sizes,id',
            'color_id' => 'nullable|integer|exists:colors,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        /* Stock Check (Cumulative) */
        $userId = auth('customer')->id();
        $sessionId = session()->getId();
        
        $currentInCart = Cart::where('product_id', $product->id)
            ->where(function ($q) use ($userId, $sessionId) {
                $q->when($userId, fn($qq) => $qq->where('user_id', $userId))
                  ->when(!$userId, fn($qq) => $qq->where('session_id', $sessionId));
            })
            ->whereJsonContains('options->size_id', $request->size_id)
            ->whereJsonContains('options->color_id', $request->color_id)
            ->sum('quantity');

        if ($product->qty < ($currentInCart + $request->qty)) {
            $available = max(0, $product->qty - $currentInCart);
            $msg = $available > 0 
                ? "You already have $currentInCart in cart. Only $available more available." 
                : "Product is out of stock or already at maximum available quantity in your cart.";
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        /* Variant calculation */
        [$sizePrice, $sizeName] = [0, null];
        [$colorPrice, $colorName] = [0, null];

        if ($request->size_id) {
            $product->load('sizes');
            $size = $product->sizes->firstWhere('id', $request->size_id);
            $sizePrice = $size->pivot->size_price ?? 0;
            $sizeName = $size->size_name ?? null;
        }

        if ($request->color_id) {
            $product->load('colors');
            $color = $product->colors->firstWhere('id', $request->color_id);
            
            if ($color) {
                $colorPrice = $color->pivot->color_price ?? 0;
                $colorName = $color->color_name ?? null;
            } else {
                // Fallback: If not in pivot, look up color globally (could be from gallery)
                $globalColor = \App\Models\Color::find($request->color_id);
                $colorName = $globalColor->color_name ?? null;
                $colorPrice = 0; // Default or could check if price exists elsewhere
            }
        }

        $variantTotal = $sizePrice + $colorPrice;
        $basePrice = $product->effective_price;

        /* Auth / Session */
        $userId = auth('customer')->id();
        $sessionId = session()->getId();

        /* Existing cart item */
        $cartItem = Cart::where('product_id', $product->id)
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when(!$userId, fn($q) => $q->where('session_id', $sessionId))
            ->whereJsonContains('options->size_id', $request->size_id)
            ->whereJsonContains('options->color_id', $request->color_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->qty);
        } else {
            $cartItem = Cart::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'product_id' => $product->id,
                'quantity' => $request->qty,
                'price' => $basePrice,
                'options' => [
                    'size_id' => $request->size_id,
                    'size_name' => $sizeName,
                    'color_id' => $request->color_id,
                    'color_name' => $colorName,
                    'variant_total' => $variantTotal,
                    'is_free_product' => false,
                ],
            ]);
        }

        // Apply promotions / free products after add
        $this->applyPromotions($this->currentCart());

        if (!$request->inertia() && ($request->ajax() || $request->wantsJson())) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
            ]);
        }

        // return redirect()->route('cart.index')->with('success', 'Product added to cart');
        return back()->with('success', 'Product added to cart successfully!');
    }


    public function index()
    {
        $cartItems = $this->currentCart();
        // dd($cartItems);
        $cartItems->each(function ($item) {
            $opt = $item->options;
            // Remove keys requested by user
            unset($opt['color_price'], $opt['size_price'], $opt['image']);
            $item->options = $opt;

            // Calculate total with robust fallback
            $basePrice = ($item->price && $item->price > 0) ? $item->price : ($item->product->effective_price ?? 0);
            $item->total = ($basePrice + ($opt['variant_total'] ?? 0)) * $item->quantity;
        });

        $total = $cartItems->sum('total');
        $promotions = $this->getPromotions($cartItems);

        // return Inertia::render('CartPage', [
        //     'initialCartItems' => $cartItems->values(),
        //     'initialTotal' => number_format($total, 2),
        //     'promotions' => $promotions,
        // ]);
        return Inertia::render('CartPage', [
            'cart_items' => $cartItems->values(),
            'total' => (float)$total,
            'promotions' => $promotions,
        ]);
    }


    public function updateCart(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::with('product')->findOrFail($request->cart_id);

        // Stock check
        if ($cart->product && $cart->product->qty < $request->quantity) {
            return back()->withErrors([
                'quantity' => 'Not enough stock available!',
            ]);
        }

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        return back();
    }

    public function removeCart(Request $request, $id)
    {
        $userId = auth('customer')->id();
        $sessionId = session()->getId();

        $cart = Cart::where('id', $id)
            ->where(function ($q) use ($userId, $sessionId) {
                $q->when($userId, fn($qq) => $qq->where('user_id', $userId))
                    ->when(!$userId, fn($qq) => $qq->where('session_id', $sessionId));
            })
            ->firstOrFail();

        $cart->delete();

        // JSON response for Axios/AJAX ONLY
        if (!$request->inertia() && ($request->ajax() || $request->wantsJson())) {
            return response()->json([
                'success' => true,
                'message' => 'Cart item removed'
            ]);
        }
        return back()->with('success', 'Cart item removed!');
    }

    public function clearCart()
    {
        $userId = auth('customer')->id();
        $sessionId = session()->getId();

        $cartItems = Cart::where(function ($q) use ($userId, $sessionId) {
            $q->when($userId, fn($qq) => $qq->where('user_id', $userId))
                ->when(!$userId, fn($qq) => $qq->where('session_id', $sessionId));
        })->get();

        Cart::where(function ($q) use ($userId, $sessionId) {
            $q->when($userId, fn($qq) => $qq->where('user_id', $userId))
                ->when(!$userId, fn($qq) => $qq->where('session_id', $sessionId));
        })->delete();

        if (!request()->inertia() && (request()->ajax() || request()->wantsJson())) {
            return response()->json(['success' => true, 'message' => 'Cart cleared successfully!']);
        }

        return back();
    }


    /* =========================
        HELPERS
    ========================= */
    private function currentCart()
    {
        $userId = auth('customer')->id();
        $sessionId = session()->getId();

        return Cart::select('id', 'user_id', 'session_id', 'product_id', 'quantity', 'price', 'options')
            ->with(['product' => function ($query) {
                // Ensure price and thumbnail are fetched from product as fallbacks
                $query->select('id', 'name', 'slug', 'thumb_image', 'qty', 'category_id', 'price', 'offer_price')
                      ->with(['campaignProducts' => function($cq) {
                        $cq->with('campaign')->whereHas('campaign', function($ccq) {
                            $ccq->where('status', 1)
                                ->where('start_date', '<=', now())
                                ->where('end_date', '>=', now());
                        });
                    }]);
            }])
            ->where(fn($q) => $userId ? $q->where('user_id', $userId) : $q->where('session_id', $sessionId))
            ->get();
    }

    /* =========================
        PROMOTIONS / FREE PRODUCTS
    ========================= */
    private function applyPromotions($cartItems = null)
    {
        $userId = auth('customer')->id();
        $sessionId = session()->getId();
        
        if (!$cartItems) {
            $cartItems = $this->currentCart();
        }

        $promotions = Promotion::where('status', 1)->get();

        foreach ($promotions as $promo) {
            $qty = $promo->product_id
                ? $cartItems->where('product_id', $promo->product_id)->sum('quantity')
                : ($promo->category_id
                    ? $cartItems->filter(fn($i) => $i->product->category_id == $promo->category_id)->sum('quantity')
                    : $cartItems->sum('quantity'));

            if ($qty >= $promo->buy_quantity && $promo->type === 'free_product' && $promo->product_id) {
                $existing = $cartItems->where('product_id', $promo->product_id)->where(fn($i) => ($i->options['is_free_product'] ?? false))->first();
                if (!$existing) {
                    $freeProduct = Product::find($promo->product_id);
                    if ($freeProduct) {
                        Cart::create([
                            'user_id' => $userId,
                            'session_id' => $userId ? null : $sessionId,
                            'product_id' => $freeProduct->id,
                            'quantity' => $promo->get_quantity ?? 1,
                            'price' => 0,
                            'options' => [
                                'image' => $freeProduct->thumb_image,
                                'variant_total' => 0,
                                'extra_price' => 0,
                                'is_free_product' => true,
                            ],
                        ]);
                    }
                }
            }
        }
    }

    private function getPromotions($cartItems)
    {
        $promotions = Promotion::where('status', 1)->get();
        $applied = [];

        foreach ($promotions as $promo) {
            $qty = $promo->product_id
                ? $cartItems->where('product_id', $promo->product_id)->sum('quantity')
                : ($promo->category_id
                    ? $cartItems->filter(fn($i) => $i->product->category_id == $promo->category_id)->sum('quantity')
                    : $cartItems->sum('quantity'));

            if ($qty >= $promo->buy_quantity) {
                $applied[] = [
                    'promotion_id' => $promo->id,
                    'type' => $promo->type,
                    'message' => $promo->type === 'free_shipping' ? 'Free Shipping!' : 'Free Product Unlocked!',
                    'free_product_id' => $promo->type === 'free_product' ? $promo->product_id : null,
                    'free_quantity' => $promo->get_quantity ?? 1,
                ];
            }
        }

        return $applied;
    }

    public function sync(Request $request)
    {
        //  'items' array check 
        $request->validate([
            'items' => 'present|array',
        ]);

        $userId = auth('customer')->id();
        $sessionId = session()->getId();

        DB::transaction(function () use ($request, $userId, $sessionId) {
            //if items is empty then delete all cart items (optional, but good)
            if (empty($request->items)) {
                Cart::where(function ($q) use ($userId, $sessionId) {
                    $q->when($userId, fn($qq) => $qq->where('user_id', $userId))
                        ->when(!$userId, fn($qq) => $qq->where('session_id', $sessionId));
                })->delete();

                return; //transaction end
            }

            //update item
            foreach ($request->items as $item) {
                Cart::where('id', $item['id'])
                    ->where(function ($q) use ($userId, $sessionId) {
                        $q->when($userId, fn($qq) => $qq->where('user_id', $userId))
                            ->when(!$userId, fn($qq) => $qq->where('session_id', $sessionId));
                    })
                    ->update(['quantity' => $item['quantity']]);
            }

            //delete remaining items (that were not synced)
            $syncedIds = collect($request->items)->pluck('id')->toArray();
            Cart::whereNotIn('id', $syncedIds)
                ->where(function ($q) use ($userId, $sessionId) {
                    $q->when($userId, fn($qq) => $qq->where('user_id', $userId))
                        ->when(!$userId, fn($qq) => $qq->where('session_id', $sessionId));
                })
                ->delete();
        });

        return response()->json(['success' => true]);
    }
}

