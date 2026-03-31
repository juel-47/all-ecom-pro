<?php

use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\CampaignController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckOutController;
use App\Http\Controllers\Frontend\FooterController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\JobApplicationController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Controllers\Frontend\SocialAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;


// Welcome page
// Route::get('/', fn() => view('welcome'))->name('welcome');

Route::get('/', [HomeController::class, 'index'])->name('home');

//auth user or customer
Route::middleware('guest:customer')->group(function () {
    Route::get('/customer/register', [AuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/customer-register', [AuthController::class, 'register']);

    Route::get('/customer/login', [AuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/customer-login', [AuthController::class, 'login'])->name('customer.login.submit');

    Route::get('/customer/forgot-password', [AuthController::class, 'showForgotPassword'])->name('customer.password.request');
    Route::post('/customer/forgot-password', [AuthController::class, 'forgotPassword'])->name('customer.password.email');

    Route::get('/customer/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('customer.password.reset');
    Route::post('/customer/reset-password', [AuthController::class, 'resetPassword'])->name('customer.password.update');

    Route::get('/resend-email', [AuthController::class, 'showResend'])
        ->name('customer.resendemail');

    //email verification route
    Route::get('/customers/verify/{id}', [AuthController::class, 'verifyEmail'])
        ->name('customers.verify');

    Route::post('/email/verification-resend', [AuthController::class, 'resendVerification'])->name('verification.resend');

    // Social Login
    Route::get('/customer/auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])
        ->name('customer.social.login');
    Route::get('/customer/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
        ->name('customer.social.callback');
});

Route::middleware(['auth:customer', 'verified'])->group(function () {

    Route::put('/update-profile', [UserDashboardController::class, 'updateProfile'])->name('update.profile');

    Route::put('/update-password', [UserDashboardController::class, 'updatePassword'])->name('update.password');

    Route::get('user-profile', [UserDashboardController::class, 'index'])->name('user.profile');
    Route::get('/user/order/{id}', [UserDashboardController::class, 'orderDetails'])->name('user.order.details');

    // =====  Customer Address Controller  =====
    // Route::apiResource('customer-billing-address', CustomerAddressController::class)->only('index', 'store', 'update', 'destroy');

    // ===== Review routes ==== 
    Route::post('review', [ReviewController::class, 'create'])->name('review.create');
});
// Route::get('/resend-email/{email?}', function ($email = null) {
//     return Inertia::render('Auth/ResendEmail', [
//         'email' => $email ?? session('verification_email')
//     ]);
// })->name('customer.resendemail');

Route::post('/customer/logout', [AuthController::class, 'logout'])->name('customer.logout')->middleware('auth:customer');

Route::get('checkout', [CheckOutController::class, 'index'])->name('checkout');

Route::get('/email/verify/{id}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify')
    ->middleware('signed');


// ===== Product Detail =====
//product details:
Route::get('product-details/{slug}', [FrontendController::class, 'productDetails'])
    ->name('product-details');
Route::get('all-products', [FrontendController::class, 'allProducts'])->name('all.products');
Route::get('category/{slug}', [FrontendController::class, 'categoryProducts'])->name('category.products');
Route::get('subcategory/{slug}', [FrontendController::class, 'subcategoryProducts'])->name('subcategory.products');
Route::get('childcategory/{slug}', [FrontendController::class, 'childcategoryProducts'])->name('childcategory.products');
Route::get('product-search', [FrontendController::class, 'productSearch'])->name('product.search');
Route::get('api/live-search', [FrontendController::class, 'liveSearch'])->name('api.live.search');

//cart controller 

Route::prefix('cart')->name('cart.')->group(function () {

    // Cart page
    Route::get('/', [CartController::class, 'index'])
        ->name('index');

    // Add to cart
    Route::post('/add', [CartController::class, 'addToCart'])
        ->name('add');

    // Update cart item quantity
    Route::post('/update', [CartController::class, 'updateCart'])
        ->name('update');

    // Remove single cart item
    Route::delete('/remove/{id}', [CartController::class, 'removeCart'])
        ->name('remove');

    // Clear full cart
    Route::delete('/clear', [CartController::class, 'clearCart'])
        ->name('clear');
});
Route::post('/cart/sync', [CartController::class, 'sync'])->name('cart.sync');
//check out controller
Route::post('/checkout', [PaymentController::class, 'store'])
    ->name('checkout.store')->middleware('auth:customer');

Route::get('/order/success/{order_id?}', [CheckOutController::class, 'success'])
    ->name('order.success')
    ->middleware('auth:customer');

// Payment Success/Cancel Routes
Route::get('paypal/success', [PaymentController::class, 'paypalSuccess'])->name('paypal.success');
Route::get('paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('paypal.cancel');
Route::get('mobilepay/success', [PaymentController::class, 'mobilePaySuccess'])->name('mobilepay.success');
Route::get('mobilepay/cancel', [PaymentController::class, 'mobilePayCancel'])->name('mobilepay.cancel');
Route::get('bkash/complete', [PaymentController::class, 'bkashSuccess'])->name('bkash.complete');
Route::get('bkash/error', [PaymentController::class, 'bkashFailed'])->name('bkash.error');

// page routes 
Route::controller(PageController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/locate-store', 'locateStore')->name('locate-store');
    Route::post('/contact', 'handleContactForm')->name('contact-form.submit');
});
//footer routes
Route::controller(FooterController::class)->group(function () {
    Route::get('/support-center', 'supportCenter')->name('support.center');
    Route::get('/how-to-order', 'howToOrder')->name('how.to.order');
    Route::get('/shipping-delivery', 'shippingDelivery')->name('shipping.delivery');
    Route::get('/return-policy', 'returnPolicy')->name('return.policy');
    Route::get('/privacy-policy', 'privacyPolicy')->name('privacy.policy');
    Route::get('/legal-notice', 'legalPolicy')->name('legal.policy');
});

//blog routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.details');

//campaign routes
Route::get('/campaign', [CampaignController::class, 'index'])->name('campaign.index');
Route::get('/campaign-products', [CampaignController::class, 'allCampaignProducts'])->name('campaign.all-products');
Route::get('/campaign/{slug}', [CampaignController::class, 'show'])->name('campaign.show');

//carrer routes
Route::get('carrer', [JobApplicationController::class, 'carrer'])->name('carrer');
Route::post('job-apply', [JobApplicationController::class, 'store'])->name('job.apply.store');

require __DIR__ . '/auth.php';

Gate::before(function ($user, $ability) {
    return $user->hasRole('SuperAdmin') ? true : null;
});

// Fallback route for 404
Route::fallback(function () {
    return Inertia::render('NotFoundPage')->toResponse(request())->setStatusCode(404);
});

