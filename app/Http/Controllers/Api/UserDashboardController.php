<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Role;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserDashboardController extends Controller
{
    use ImageUploadTrait;

    public function userOrders()
    {
        $totalOrder = Order::where('customer_id', Auth::user('sanctum')->id)->count();
        // $order_address=Order::where('customer_id',Auth::user('sanctum')->id)->get();
        $orders = Order::with(
            [
                'orderProducts' => function ($query) {
                    $query->select('id', 'order_id', 'product_id', 'product_name', 'variants', 'variants_total', 'unit_price', 'qty', 'extra_price', 'front_image', 'back_image',);
                },
                'orderStatus' => function ($query) {
                    $query->select('id', 'name', 'slug');
                },
                'transaction' => function ($query) {
                    $query->select('id', 'order_id', 'transaction_id');
                }
            ]
        )->where('customer_id', Auth::user('sanctum')->id)
            ->select('id', 'invoice_id', 'customer_id', 'sub_total', 'amount', 'currency_name', 'currency_icon', 'product_qty', 'payment_method', 'payment_status', 'order_address', 'shipping_method', 'coupon', 'order_status_id')
            ->get();
        return response([
            'totalOrder' => $totalOrder,
            'orders' => $orders
        ]);
    }
    public function updateProfile(Request $request)
    {
        $user = $request->user('sanctum');

        // Validation rules
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', Rule::unique(Customer::class)->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique(Customer::class)->ignore($user->id)],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        // Check email uniqueness within same role scope
        $emailExists = Customer::whereHas('roles', function ($q) use ($user) {
            $q->whereIn('name', $user->getRoleNames());
        })
            ->where('email', $validated['email'])
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email already taken for this role.',
            ], 422);
        }

        // Handle image upload (only if provided)
        if ($request->hasFile('image')) {
            $validated['image'] = $this->update_image(
                $request,
                'image',
                'uploads/user_profile_update',
                $user->image
            );
        }

        // Fill and update user data
        $user->fill($validated);

        // Reset email verification if email changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Return updated profile data
        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'image' => $user->image ? asset($user->image) : null,
            ],
        ]);
    }


    public function updatePassword(Request $request)
    {
        $user = $request->user('sanctum');

        // Validate request
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'confirmed'], // confirmed expects new_password_confirmation
        ]);

        // Check if current password matches
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Current password is incorrect.'
            ], 422);
        }

        // Update password
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully.'
        ]);
    }
}
