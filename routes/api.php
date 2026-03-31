<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckOutController;
use App\Http\Controllers\Api\CustomerAddressController;
use App\Http\Controllers\Api\FooterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FrontendProductController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserDashboardController;


/*
|--------------------------------------------------------------------------
| API Versioning: v1
|--------------------------------------------------------------------------
*/

// Route::prefix('v1')->name('api.v1.')->group(function () {

//     // Authenticated user route
//     Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//         return $request->user();
//     })->name('user');


//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
//     Route::post('/reset-password-user', [AuthController::class, 'resetPassword']);
//     // Email verification route
//     Route::get('/customers/verify/{id}', [AuthController::class, 'verifyEmail'])
//         ->name('customers.verify');
//     Route::post('/customers/resend-verification', [AuthController::class, 'resendVerificationEmail']);


//     Route::middleware('auth:sanctum')->group(function () {
//         Route::post('/logout', [AuthController::class, 'logout']);
//         Route::get('/user', [AuthController::class, 'user']);

//         Route::post('/update-profile', [UserDashboardController::class, 'updateProfile']);
//         Route::post('/update-password', [UserDashboardController::class, 'updatePassword']);

//         Route::get('user-orders', [UserDashboardController::class, 'userOrders']);

//         // =====  Customer Address Controller  =====
//         Route::apiResource('customer-billing-address', CustomerAddressController::class)->only('index', 'store', 'update', 'destroy');

//         // ===== Review routes ==== 
//         Route::post('review', [ReviewController::class, 'create'])->name('review.create');
//     });

//     // ===== Home Page =====
//     // Route::get('/home', [HomeController::class, 'index'])->name('home');
//     Route::controller(HomeController::class)->prefix('home')->group(function () {
//         Route::get('/sliders', 'sliders');
//         Route::get('/brands', 'brands');
//         Route::get('/categories', 'categories');
//         Route::get('/products', 'homeProducts');
//         Route::get('/products-by-type', 'getTypeBaseProduct');
//         Route::get('/logo-fav', 'logos');
//         Route::get('/colors', 'colors');
//         Route::get('/sizes', 'sizes');
//         Route::get('/site-setting', 'settings');
//     });

//     // ===== Products =====

//     // ===== Product Detail =====
//     //product details:
//     Route::get('product-detail/{slug}', [FrontendProductController::class, 'productDetails'])
//         ->name('product-detail');
//     //product category 
//     Route::get('category/{slug}', [FrontendProductController::class, 'categoryProducts'])->name('categoryProducts');
//     //product subcategory
//     Route::get('subcategory/{slug}', [FrontendProductController::class, 'subcategoryProducts'])->name('subcategory.products');
//     //product childcategory
//     Route::get('childcategory/{slug}', [FrontendProductController::class, 'childcategoryProducts'])->name('childcategory.products');
//     //all products
//     Route::get('all-products', [FrontendProductController::class, 'allProducts'])->name('all.products');


//     Route::get('checkout', [CheckOutController::class, 'index'])->name('checkout');

//     // Route::get('/uploads/customizations/{filename}', function ($filename) {
//     //     $path = public_path('uploads/customize/' . $filename);

//     //     if (!file_exists($path)) {
//     //         abort("not working");
//     //     }

//     //     return response()->file($path, [
//     //         'Access-Control-Allow-Origin' => '*', // Development
//     //     ]);
//     // });

//     Route::middleware('auth:sanctum')->group(function () {

//         // // Store or update customer customization
//         // Route::post('product-customize', [CustomerCustomizationController::class, 'storeOrUpdate'])->middleware('auth:sanctum')->name('product-customize.store');
//         // // Serve uploaded images with CORS
//         // Route::get('/uploads/customizations/{filename}', function ($filename) {
//         //     $path = public_path('uploads/customizations/' . $filename);
//         //     if (!file_exists($path)) abort(404);
//         //     return response()->file($path, [
//         //         'Access-Control-Allow-Origin' => '*',
//         //     ]);
//         // });


//         // // =====  Cart Controller  =====
//         // Route::controller(CartController::class)->prefix('cart')->group(function () {

//         //     // Add product to cart
//         //     Route::post('/add', 'addToCart')->name('cart.add');

//         //     // Get all cart items
//         //     Route::get('/details', 'getCart')->name('cart.get.details');

//         //     // Update cart item quantity
//         //     Route::post('/update', 'updateCart')->name('cart.update');

//         //     // Remove single item
//         //     Route::delete('/remove/{id}', 'removeCart')->name('cart.remove');

//         //     // Clear entire cart
//         //     Route::delete('/clear', 'clearCart')->name('cart.clear');

//         //     // Cart subtotal (only total)
//         //     // Route::get('/total', 'cartTotal')->name('cart.total');

//         //     // Cart count
//         //     // Route::get('/count', 'cartCount')->name('cart.count');

//         //     // Apply coupon and calculate totals
//         //     Route::post('/apply-coupon', 'calculateCoupon')->name('cart.coupon');

//         //     // Full summary for checkout (optional but very useful)
//         //     Route::get('/summary', 'cartSummary')->name('cart.summary');
//         // });

//         /** payment routes */
//         Route::controller(PaymentController::class)->group(function () {

//             // Route::get('payment', 'index')->name('payment');
//             Route::get('payment/success', 'paymentSuccess')->name('payment.success');

//             /** Paypal Payment routes */
//             Route::post('paypal/payment', 'payWithPaypal')->name('paypal-payment');
//             Route::get('paypal/success', 'paypalSuccess')->name('paypal-success');
//             Route::get('paypal/cancel', 'paypalCancel')->name('paypal-cancel');

//             /** Stripe Payment routes */
//             // Route::post('stripe/payment', 'payWithStripe')->name('stripe-payment');

//             /** cod payment route */
//             Route::post('cod-payment', 'payWithCod')->name('cod-payment');

//             /** payonner payment route */
//             Route::post('payoneer/pay', [PaymentController::class, 'payWithPayoneer'])->name('payoneer-pay');
//             Route::get('payoneer/success', [PaymentController::class, 'payoneerSuccess'])->name('payoneer-success');
//             Route::get('payoneer/cancel', [PaymentController::class, 'payoneerCancel'])->name('payoneer-cancel');

//             /** mobile pay route */
//             Route::post('/mobilepay', [PaymentController::class, 'payWithMobilePay'])->name('mobilepay.pay');
//             Route::get('/mobilepay/success', [PaymentController::class, 'mobilePaySuccess'])->name('mobilepay.success');
//             Route::get('/mobilepay/cancel', [PaymentController::class, 'mobilePayCancel'])->name('mobilepay.cancel');
//             Route::post('/mobilepay/webhook', [PaymentController::class, 'webhook'])->name('mobilepay.webhook');
//         });
//     });

//     // ===== about & terms and condition & contact & branch routes  =====

//     Route::controller(PageController::class)->group(function () {
//         Route::get('/about', 'about')->name('about');
//         Route::get('/terms-and-conditions', 'termsAndCondition')->name('terms-and-conditions');
//         Route::get('/contact-branch', 'branch')->name('contact');
//         Route::post('/contact-us', 'handleContactForm')->name('contact-form.submit');
//     });

//     // ====== footer info =====
//     Route::controller(FooterController::class)->group(function () {
//         Route::get('/footer-info', 'footerInfo')->name('footer-info');
//     });



//     Route::get('/uploads/customizations/{filename}', function ($filename) {
//         $path = public_path('uploads/customizations/' . $filename);
//         if (!file_exists($path)) abort(404);
//         return response()->file($path, [
//             'Access-Control-Allow-Origin' => '*',
//         ]);
//     });

//     // =====  Cart Controller  ===== 
//     Route::controller(CartController::class)->prefix('cart')->group(function () {
//         // Add product to cart
//         Route::post('/add', 'addToCart')->name('cart.add');

//         // Get all cart items
//         Route::get('/details', 'getCart')->name('cart.get.details');

//         // Update cart item quantity
//         Route::post('/update', 'updateCart')->name('cart.update');

//         // Remove single item
//         Route::delete('/remove/{id}', 'removeCart')->name('cart.remove');

//         // Clear entire cart
//         Route::delete('/clear', 'clearCart')->name('cart.clear');

//         // Apply coupon and calculate totals
//         Route::post('/apply-coupon', 'calculateCoupon')->name('cart.coupon');

//         // Full summary for checkout
//         Route::get('/summary', 'cartSummary')->name('cart.summary');
//     });
//     //job appliction routes
//     Route::post('job-apply', [JobApplicationController::class, 'store']);
    
// });