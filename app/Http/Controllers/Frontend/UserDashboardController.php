<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

// class UserDashboardController extends Controller
// {
//     //
// }

class UserDashboardController extends Controller
{
    use ImageUploadTrait;

    public function index()
    {
        
        $user = Auth::user(); // Session auth
        // dd($user);
        $totalOrder = Order::where('customer_id', $user->id)->count();

        $orders = Order::with([
                'orderProducts' => fn($query) => $query->select('id', 'order_id', 'product_id', 'product_name', 'variants', 'variants_total', 'unit_price', 'qty', 'extra_price', 'front_image', 'back_image'),
                'orderStatus' => fn($query) => $query->select('id', 'name', 'slug'),
                'transaction' => fn($query) => $query->select('id', 'order_id', 'transaction_id'),
            ])
            ->where('customer_id', $user->id)
            ->select('id', 'invoice_id', 'customer_id', 'sub_total', 'amount', 'currency_name', 'currency_icon', 'product_qty', 'payment_method', 'payment_status', 'order_address', 'shipping_method', 'coupon', 'order_status_id')
            ->get();

        return Inertia::render('UserProfile', [ 
            'totalOrder' => $totalOrder,
            'ordersData' => $orders,
            'userData' => $user->only('name', 'email', 'image', 'phone', 'username', 'address'), 
        ]);
    }
    public function updateProfile(Request $request)
    {
        // dd($request->all());
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', Rule::unique('customers')->ignore($user->id)], // Adjust table if needed
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('customers')->ignore($user->id)],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        // Email uniqueness check (same as before)
        $emailExists = Customer::whereHas('roles', fn($q) => $q->whereIn('name', $user->getRoleNames()))
            ->where('email', $validated['email'])
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'Email already taken for this role.',
            ]);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $this->update_image(
                $request,
                'image',
                'uploads/user_profile_update',
                $user->getRawOriginal('image')
            );
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->route('user.profile')->with('success', 'Password updated successfully');
    }

    public function orderDetails($id)
    {
        $user = Auth::user();
        $order = Order::where('id', $id)
            ->where('customer_id', $user->id)
            ->with([
                'orderProducts',
                'orderStatus',
                'transaction'
            ])
            ->firstOrFail();

        return Inertia::render('OrderDetails', [
            'order' => $order
        ]);
    }
}
