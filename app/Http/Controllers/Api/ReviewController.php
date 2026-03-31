<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'review'     => 'required|string|max:200',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        //if user has already submitted a review for this product

        // $exists = ProductReview::where([
        //     'user_id'    => $user->id,
        //     'product_id' => $validated['product_id']
        // ])->first();

        // if ($exists) {
        //     return response()->json([
        //         'status'  => false,
        //         'message' => 'You have already submitted a review for this product'
        //     ], 409);
        // }

        $review = ProductReview::create([
            'product_id' => $validated['product_id'],
            'user_id'    => $user->id,
            'rating'     => $validated['rating'],
            'review'     => $validated['review'],
            'status'     => 0
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Review submitted successfully',
            'data'    => $review
        ], 201);
    }
}
