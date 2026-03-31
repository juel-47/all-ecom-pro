<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\Cart;
use App\Models\Customer;
use App\Notifications\CustomVerifyEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

// class AuthController extends Controller
// {

// }

class AuthController extends Controller
{
    // Register Page
    public function showRegister()
    {
        return Inertia::render('Register');
    }

    public function register(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Send verification email
        $customer->notify(new CustomVerifyEmail());

        // Auto login after register (optional, but common)
        // Auth::guard('customer')->login($customer);

        return Inertia::location(route('customer.resendemail', ['email' => $request->email]));
        // return redirect()->route('home')->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    // Login Page
    public function showLogin()
    {
        return Inertia::render('Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        if (!$customer->hasVerifiedEmail()) {
            return back()->withErrors(['email' => 'Please verify your email first.'])
                ->with('resend_email', $customer->email);
        }

        if ($customer->status !== 'active') {
            return back()->withErrors(['email' => 'Your account is inactive. Contact support.']);
        }

        // ✅ IMPORTANT: login session
        $oldSessionId = session()->getId();

        // LOGIN
        Auth::guard('customer')->login($customer);

        // ✅ MERGE using OLD session id
        $this->mergeGuestCartToUser($customer->id, $oldSessionId);

        return redirect()->intended(route('home'))
            ->with('success', 'Welcome back, ' . $customer->name . '!');
    }


    // Resend Verification Email
    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:customers,email']);

        $customer = Customer::where('email', $request->email)->first();

        if ($customer->hasVerifiedEmail()) {
            return back()->with('info', 'Email already verified.');
        }

        $customer->notify(new CustomVerifyEmail());

        return back()->with('success', 'Verification email sent again!');
    }

    // Email Verification Handler (click from email link)
    public function verifyEmail(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        if (! $request->hasValidSignature()) {
            return redirect()->route('customer.login')->with('error', 'Invalid or expired verification link.');
        }

        if ($customer->hasVerifiedEmail()) {
            return redirect()->route('home')->with('info', 'Email already verified.');
        }

        $customer->markEmailAsVerified();

        // Auto login after verification
        Auth::guard('customer')->login($customer);

        return redirect()->route('home')->with('success', 'Email verified successfully! Welcome!');
    }

    // Forgot Password Page
    public function showForgotPassword()
    {
        return Inertia::render('ForgotPasswordPage');
    }

    public function forgotPassword(Request $request)
    {
        // dd($request->all());
        $request->validate(['email' => 'required|email|exists:customers,email']);

        $customer = Customer::where('email', $request->email)->first();

        // Clear old tokens
        DB::table('password_reset_tokens')->where('email', $customer->email)->delete();

        $plainToken = Str::random(60);

        DB::table('password_reset_tokens')->insert([
            'email' => $customer->email,
            'token' => Hash::make($plainToken),
            'created_at' => Carbon::now(),
        ]);

        Mail::to($customer->email)->send(new ResetPasswordMail($plainToken, $customer->email));

        return back()->with('success', 'Password reset link sent to your email!');
    }

    // Reset Password Page
    public function showResetPassword($token)
    {
        return Inertia::render('ResetPassword', [
            'token' => $token,
            'email' => request('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return back()->withErrors(['token' => 'Invalid or expired token']);
        }

        $customer = Customer::where('email', $request->email)->first();
        $customer->password = Hash::make($request->password);
        $customer->save();

        // Delete all tokens (security)
        $customer->tokens()->delete();

        // Delete used reset token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('customer.login')->with('success', 'Password reset successful! Please login.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->forget('cart');
        return redirect()->route('home')->with('success', 'Logged out successfully!');

        // return redirect()->route('home')->with('success', 'Logged out successfully!');
    }

    // Private helpers (same as before)
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
    public function showResend(Request $request)
    {
        // dd($request->all());
        $email = $request->query('email');
        return Inertia::render('ResendEmail', [
            'email' => $email
        ]);
    }
}
