<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

// class CartService
// {
//     public function getCartItems()
//     {
//         return Cart::with(['product']) // প্রয়োজনে আরো রিলেশন যোগ করো, যেমন customization
//             ->where(function ($query) {
//                 if (Auth::guard('customer')->check()) {
//                     $query->where('user_id', Auth::guard('customer')->id());
//                 } else {
//                     $query->where('session_id', Session::getId());
//                 }
//             })
//             ->get();
//     }

//     public function getCartTotal($cartItems = null)
//     {
//         if (!$cartItems) {
//             $cartItems = $this->getCartItems();
//         }

//         return $cartItems->sum(function ($item) {
//             $basePrice    = $item->price;
//             $variantTotal = $item->options['variant_total'] ?? 0;
//             $extraPrice   = $item->options['extra_price'] ?? 0;
//             return ($basePrice + $variantTotal + $extraPrice) * $item->quantity;
//         });
//     }

//     public function getCartCount()
//     {
//         return $this->getCartItems()->sum('quantity');
//     }

//     public function getCartSummary()
//     {
//         $items = $this->getCartItems();
//         $total = $this->getCartTotal($items);

//         return [
//             'items'   => $items,
//             'count'   => $items->sum('quantity'),
//             'total'   => number_format($total, 2, '.', ''),
//         ];
//     }
// }

//only cart count : 
class CartService
{
    protected function getQuery()
    {
        return Cart::query()
            ->where(function ($query) {
                if (Auth::guard('customer')->check()) {
                    $query->where('user_id', Auth::guard('customer')->id());
                } else {
                    $query->where('session_id', Session::getId());
                }
            });
    }

    public function getCartCount(): int
    {
        return $this->getQuery()->count();
        // return $this->getQuery()->sum('quantity');
    }

    public function getCartTotal(): float
    {
        $items = $this->getQuery()->select('price', 'quantity', 'options')->get();

        return $items->sum(function ($item) {
            $basePrice    = $item->price;
            $variantTotal = $item->options['variant_total'] ?? 0;
            return ($basePrice + $variantTotal) * $item->quantity;
        });
    }

    // Navbar cart info
    public function getNavbarCartInfo()
    {
        return [
            'count' => $this->getCartCount(),
            'total' => number_format($this->getCartTotal(), 2),
        ];
    }
}