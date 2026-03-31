<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param  string $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param  string $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            Log::error("Social login error ($provider): " . $e->getMessage());
            return redirect()->route('customer.login')->with('error', 'Login with ' . ucfirst($provider) . ' failed. Please try again.');
        }

        // Find existing customer by email or provider ID
        $customer = Customer::where('email', $socialUser->getEmail())
            ->orWhere("{$provider}_id", $socialUser->getId())
            ->first();

        if (!$customer) {
            // Register new customer
            $customer = Customer::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                "{$provider}_id" => $socialUser->getId(),
                'status' => 'active',
                'password' => null, // No password for social users
                'email_verified_at' => now(), // Assume email from social provider is verified
            ]);
        } else {
            // Update existing customer with provider ID if missing
            if (empty($customer->{"{$provider}_id"})) {
                $customer->update([
                    "{$provider}_id" => $socialUser->getId(),
                ]);
            }
             // Ensure email is marked verified if it wasn't
             if (!$customer->hasVerifiedEmail()) {
                 $customer->markEmailAsVerified();
             }
        }

        if ($customer->status !== 'active') {
             return redirect()->route('customer.login')->with('error', 'Your account is inactive. Contact support.');
        }

        // Important: Cart merging logic (copied from AuthController)
        $oldSessionId = session()->getId();
        
        Auth::guard('customer')->login($customer);

        $this->mergeGuestCartToUser($customer->id, $oldSessionId);

        return redirect()->route('home')->with('success', 'Logged in with ' . ucfirst($provider) . ' successfully!');
    }

    /**
     * Merge guest cart to user (Same as AuthController).
     * Ideally, this should be in a Service or Helper to avoid duplication.
     */
    private function mergeGuestCartToUser($userId, $sessionId)
    {
        $guestCart = Cart::where('session_id', $sessionId)->get();

        foreach ($guestCart as $item) {

            $existing = Cart::where('user_id', $userId)
                ->where('product_id', $item->product_id)
                ->where('options->size_id', $item->options['size_id'] ?? null)
                ->where('options->color_id', $item->options['color_id'] ?? null)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $item->quantity);
                $item->delete();
            } else {
                $item->update([
                    'user_id' => $userId,
                    'session_id' => null,
                ]);
            }
        }
    }
}
