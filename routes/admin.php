<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AdminListController;
use App\Http\Controllers\Backend\AttendanceController;
use App\Http\Controllers\Backend\AiChatController;
use App\Http\Controllers\Backend\AiIntegrationController;
use App\Http\Controllers\Backend\CourierIntegrationController;
use App\Http\Controllers\Backend\BlogCategoryController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\BranchController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\BkashSettingController;
use App\Http\Controllers\Backend\CampaignController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ChildCategoryController;
use App\Http\Controllers\Backend\CodSettingController;
use App\Http\Controllers\Backend\ColorController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\CreatePageController;
use App\Http\Controllers\Backend\CustomerListController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\FlashSaleController;
use App\Http\Controllers\Backend\FooterInfoController;
use App\Http\Controllers\Backend\FooterSocialController;
use App\Http\Controllers\Backend\JobApplicationController;
use App\Http\Controllers\Backend\MobilePaySettingController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\OrderStatusController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\PaymentSettingController;
use App\Http\Controllers\Backend\PayoneerSettingController;
use App\Http\Controllers\Backend\PaypalSettingController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\PickupShippingController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\SmsIntegrationController;
use App\Http\Controllers\Backend\SteadfastSettingController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductImageGalleryController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\PromotionController;
use App\Http\Controllers\Backend\ReviewController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\ShippingController;
use App\Http\Controllers\Backend\ShippingRuleController;
use App\Http\Controllers\Backend\SizeController;
use App\Http\Controllers\Backend\StateController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Frontend\HomeController;
use App\Models\Order;
use App\Models\Slider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;
use Intervention\Image\Facades\Image;

Route::middleware(['web', 'auth', 'verified', 'check.permission'])->prefix('admin')->name('admin.')->group(function () {
    /** admin routes */
    Route::middleware(['web', 'auth', 'verified'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    });
    Route::middleware(['web', 'auth', 'verified',])->group(function () {
        /** profile routes */
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'index')->name('profile');
            Route::post('/profile/update', 'updateProfile')->name('profile.update');
            Route::post('/profile/update/password', 'updatePassword')->name('password.update');
        });
        /** slider route */
        Route::resource('slider', SliderController::class);
        /** category routes */
        Route::put('category/change-status', [CategoryController::class, 'changeStatus'])->name('category.change-status');
        Route::put('category/front-show', [CategoryController::class, 'frontShow'])->name('category.front-show');
        Route::resource('category', CategoryController::class);

        /** subcategory routes */
        Route::put('subcategory/change-status', [SubCategoryController::class, 'changeStatus'])->name('subcategory.change-status');
        Route::resource('sub-category', SubCategoryController::class);

        /** child category routes */
        Route::controller(ChildCategoryController::class)->group(function () {
            Route::put('child-category/change-status', 'changeStatus')->name('child-category.change-status');
            Route::get('get-subcategories', 'getSubCategories')->name('get-subCategories');
        });
        Route::resource('child-category', ChildCategoryController::class);

        /** brand routes */
        Route::put('brand/change-status', [BrandController::class, 'changeStatus'])->name('brand.change-status');
        Route::resource('brand', BrandController::class);

        /** product routes */
        Route::put('products/change-status', [ProductController::class, 'changeStatus'])->name('products.change-status');
        Route::get('products/get-sub-categories', [ProductController::class, 'getSubCategories'])->name('product.get-sub-categories');
        Route::get('products/get-child-categories', [ProductController::class, 'getChildCategories'])->name('product.get-child-categories');

        /** product pending */
        Route::get('products_pending',  [ProductController::class, 'productsPending'])->name('product-pending.index');
        Route::put('products_pending',  [ProductController::class, 'changeApproveStatus'])->name('change-approve-status');
        /** out of stock */
        Route::get('products_out_of_stock',  [ProductController::class, 'OutOfStock'])->name('product_out_of_stock.index');
        Route::resource('products', ProductController::class);

        /** product image gallery routes */
        Route::controller(ProductImageGalleryController::class)->group(function () {
            Route::get('products-image-gallery', 'index')->name('products-image-gallery.index');
            Route::post('products-image-gallery', 'store')->name('products-image-gallery.store');
            Route::delete('products-image-gallery/{id}', 'destroy')->name('products-image-gallery.destroy');
        });
        // /* Product Variant routes */
        // Route::put('product-variant/change-status', [ProductVariantController::class, 'changeStatus'])->name('product-variant.change-status');
        // Route::resource('product-variant', ProductVariantController::class);
        // /* Product Variant Item routes */
        // Route::controller(ProductVariantItemController::class)->group(function () {
        //     Route::get('products-variant-item/{productId}/{variantId}', 'index')->name('products-variant-item.index');
        //     Route::get('products-variant-item/create/{productId}/{variantId}', 'create')->name('products-variant-item.create');
        //     Route::post('products-variant-item', 'store')->name('products-variant-item.store');
        //     Route::get('products-variant-item-edit/{variantItemId}', 'edit')->name('products-variant-item.edit');
        //     Route::put('products-variant-item-update/{variantItemId}', 'update')->name('products-variant-item.update');
        //     Route::delete('products-variant-item/{variantItemId}', 'destroy')->name('products-variant-item.destroy');
        //     Route::put('products-variant-item-status', 'changeStatus')->name('products-variant-item.change-status');
        // });
        /** size routes */
        Route::put('size/change-status', [SizeController::class, 'changeStatus'])->name('size.change-status');
        Route::resource('size', SizeController::class);

        /** color routes */
        Route::put('color/change-status', [ColorController::class, 'changeStatus'])->name('color.change-status');
        Route::resource('color', ColorController::class);

        /** Flash Sale */
        Route::controller(FlashSaleController::class)->group(function () {
            Route::get('flash-sale', 'index')->name('flash-sale.index');
            Route::put('flash-sale', 'update')->name('flash-sale.update');
            Route::post('flash-sale/add-product', 'addProduct')->name('flash-sale.add-product');
            Route::put('flash-sale/change-status', 'changeStatus')->name('flash-sale.change-status');
            Route::put('flash-sale/show_at_home/change-status', 'ShowAtHomeChangeStatus')->name('flash-sale.show_at_home.change-status');
            Route::delete('flash-sale/{id}', 'destroy')->name('flash-sale.destroy');
        });

        /** setting routes */
        Route::controller(SettingController::class)->group(function () {
            Route::get('settings', 'index')->name('setting.index');
            Route::put('general-setting', 'generalSettingUpdate')->name('general-setting.update');
            Route::put('email-configuration-setting', 'emailConfigurationUpdate')->name('email-configuration-setting.update');
            Route::put('log-setting', 'logSettingUpdate')->name('log-setting.update');
        });

        /** coupon routes */
        Route::put('coupons/change-status', [CouponController::class, 'changeStatus'])->name('coupon.change-status');
        Route::resource('coupons', CouponController::class);

        /** shipping rule route */
        Route::put('shipping-rule/change-status', [ShippingRuleController::class, 'changeStatus'])->name('shipping-rule.change-status');
        Route::resource('shipping-rule', ShippingRuleController::class);

        /** shipping method routes */
        // Route::controller(ShippingController::class)->group(function () {
        //     // Shipping Method
        //     Route::get('shipping-methods', 'index')->name('shipping.index');
        //     Route::get('shipping-methods/create', 'create')->name('shipping.create');
        //     Route::post('shipping-methods/store', 'store')->name('shipping.store');
        //     Route::get('shipping-methods/{shipping}/edit', 'edit')->name('shipping.edit');
        //     Route::put('shipping-methods/{shipping}/update', 'update')->name('shipping.update');
        //     Route::delete('shipping-methods/{shipping}/delete', 'destroy')->name('shipping.destroy');

        // });
        // // shipping method with country and state routes
        // Route::put('shipping-methods/change-status', [ShippingController::class, 'changeStatus'])->name('shipping-methods.change-status');

        // Route::get('shipping-methods/{shipping}/charge', [ShippingController::class, 'chargeForm'])->name('shipping.charge');
        // Route::post('shipping-methods/{shipping}/charge', [ShippingController::class, 'saveCharge'])->name('shipping.charge.save');
        // Route::delete('shipping-charges/{charge}/delete', [ShippingController::class, 'deleteCharge'])->name('shipping.charge.delete');

        // Route::resource('shipping-methods', ShippingController::class);

        // /** country routes */
        // Route::put('countries/change-status', [CountryController::class, 'changeStatus'])->name('countries.change-status');
        // Route::get('/get-countries', [CountryController::class, 'getCountries'])->name('get.countries');
        // Route::resource('countries', CountryController::class);

        // /** state routes */
        // Route::put('states/change-status', [StateController::class, 'changeStatus'])->name('states.change-status');
        // Route::get('/get-states', [StateController::class, 'getStates'])->name('get.states');
        // Route::resource('states', StateController::class);


        /** payment routes */
        Route::get('payment-settings', [PaymentSettingController::class, 'index'])->name('payment-settings.index');
        Route::get('integrations/courier', [CourierIntegrationController::class, 'index'])->name('integrations.courier');
        Route::get('integrations/sms', [SmsIntegrationController::class, 'index'])->name('integrations.sms');
        Route::put('integrations/sms/{id}', [SmsIntegrationController::class, 'update'])->name('integrations.sms.update');
        Route::get('integrations/ai', [AiIntegrationController::class, 'index'])->name('integrations.ai');
        Route::put('integrations/ai/{id}', [AiIntegrationController::class, 'update'])->name('integrations.ai.update');
        Route::post('ai/chat', [AiChatController::class, 'send'])->name('ai.chat');

        /** paypal payment setting route */
        Route::put('paypal-setting/{id}', [PaypalSettingController::class, 'update'])->name('paypal-setting.update');
        // Route::controller(PaypalSettingController::class)->group(function () {
        //     Route::get('paypal-setting', 'index')->name('paypal-settings.index');
        //     Route::put('paypal-setting', 'update')->name('paypal-setting.update');
        // });

        Route::put('payoneer-setting/{id}', [PayoneerSettingController::class, 'update'])->name('payoneer-setting.update');

        Route::put('/mobile-pay-setting/{id}', [MobilePaySettingController::class, 'update'])->name('mobile-pay-setting.update');

        /** Cod payment setting route */
        Route::put('cod-setting/{id}', [CodSettingController::class, 'update'])->name('cod-setting.update');

        /** bKash payment setting route */
        Route::put('bkash-setting/{id}', [BkashSettingController::class, 'update'])->name('bkash-setting.update');

        /** Steadfast courier setting route */
        Route::put('steadfast-setting/{id}', [SteadfastSettingController::class, 'update'])->name('steadfast-setting.update');

        /** order status route */
        Route::put('order-status/change-status', [OrderStatusController::class, 'changeStatus'])->name('order-status.change-status');
        Route::resource('order-status', OrderStatusController::class);

        /** order routes */
        Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
        // Route::get('/orders/status/{id}', [OrderController::class, 'statusWise'])->name('order.status');
        Route::get('/orders/status/{id}', [OrderController::class, 'statusOrders'])->name('order.status');
        Route::get('order-status-change', [OrderController::class, 'changeOrderStatus'])->name('order-status');

        // Route::get('/orders/data/{statusId?}', [OrderController::class, 'getData'])->name('order.data');

        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.show');
        Route::post('/orders/{id}/steadfast', [OrderController::class, 'sendToSteadfast'])
            ->name('orders.steadfast')
            ->middleware('steadfast.config');
        Route::post('/orders/steadfast/bulk', [OrderController::class, 'bulkSendToSteadfast'])
            ->name('orders.steadfast.bulk')
            ->middleware('steadfast.config');
        Route::get('payment-status', [OrderController::class, 'changePaymentStatus'])->name('payment-status');
        Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('order.destroy');

        /** Order Transaction */
        Route::get('transaction', [TransactionController::class, 'index'])->name('transaction');
        Route::get('transaction-mobilepay', [TransactionController::class, 'mobilePayTransaction'])->name('mobilepay-transaction');

        /** promation route */
        Route::put('promotions/change-status', [PromotionController::class, 'changeStatus'])->name('promotions.change-status');
        Route::resource('promotions', PromotionController::class);


        /** blog category route */
        Route::put('blog-category/change-status', [BlogCategoryController::class, 'changeStatus'])->name('blog-category.change-status');
        Route::put('blog-category/front-show', [BlogCategoryController::class, 'frontShow'])->name('blog-category.front-show');
        Route::resource('blog-category', BlogCategoryController::class);

        /** blog route */
        Route::put('blog/change-status', [BlogController::class, 'changeStatus'])->name('blog.change-status');
        Route::resource('blog', BlogController::class);

        /**blog comment */
        // Route::get('blog-comment', [BlogCommentController::class, 'index'])->name('blog-comment.index');
        // Route::delete('blog-comment/{id}', [BlogCommentController::class, 'destroy'])->name('blog-comment.destroy');

        /** reviews route */
        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::put('reviews/change-status', [ReviewController::class, 'changeStatus'])->name('reviews.change-status');
        Route::delete('reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

        /** footer info route */
        Route::resource('footer-info', FooterInfoController::class)->only('index', 'update');

        /** Footer social routes */
        Route::put('footer-social/change-status', [FooterSocialController::class, 'changeStatus'])->name('footer-social.change-status');
        Route::resource('footer-socials', FooterSocialController::class);

        /** customer list route */
        Route::controller(CustomerListController::class)->group(function () {
            Route::get('/customer-list', 'index')->name('customer.index');
            Route::put('/change-status', 'changeStatus')->name('customer.change-status');
        });

        /** admin list route */
        Route::controller(AdminListController::class)->group(function () {
            Route::get('/admin-list', 'index')->name('admin_list.index');
            Route::put('/admin-list/change-status', 'changeStatus')->name('admin_list.change-status');
            Route::delete('/admin-list/{id}/destroy', 'destroy')->name('admin_list.destroy');
        });


        /** page routes */
        Route::controller(PageController::class)->group(function () {
            Route::get('/about', 'about')->name('about');
            Route::put('/about/update', 'aboutUpdate')->name('about.update');
            Route::get('/terms-and-condition', 'termsAndCondition')->name('terms-and-condition');
            Route::put('/terms-and-condition/update', 'termsAndConditionUpdate')->name('terms-and-condition.update');
        });
        /** create pages routes */
        Route::put('create-page/change-status', [CreatePageController::class, 'changeStatus'])->name('create-page.change-status');
        Route::resource('create-page', CreatePageController::class);

        /** branch  route */
        Route::put('branch/change-status', [BranchController::class, 'changeStatus'])->name('branch.change-status');
        Route::resource('branch', BranchController::class);

        /** authorization route */
        // Route::group(['middleware' => ['permission:Administration']], function () {

        // });
        Route::put('users/change-status', [UserController::class, 'changeStatus'])->name('users.change-status');
        Route::resource('users', UserController::class);
        Route::resource('role', RolesController::class);
        Route::resource('permission', PermissionController::class);

        /** employee controller */
        Route::put('employees/change-status', [UserController::class, 'changeStatus'])->name('employees.change-status');
        Route::get('employees/{employee}/summary', [EmployeeController::class, 'summary'])
            ->name('employees.summary');
        Route::resource('employees', EmployeeController::class);

        /** job application */
        Route::get('job-application', [JobApplicationController::class, 'index'])->name('job-application.index');
        Route::get('job-application/{id}/show', [JobApplicationController::class, 'viewPdf'])->name('job-application.show');
        Route::delete('job-application/{id}/destroy', [JobApplicationController::class, 'destroy'])->name('job-application.destroy');
        Route::get('job-applications/{id}/download', [JobApplicationController::class, 'download'])->name('job-application.download');

        /** pickup_shipping route */
        Route::resource('pickup-shipping', PickupShippingController::class);

        /** Campaign routes */
        Route::controller(CampaignController::class)->group(function () {
            Route::put('campaign/change-status', 'changeStatus')->name('campaign.change-status');
            Route::get('campaign/{id}/products', 'productsIndex')->name('campaign.products.index');
            Route::post('campaign/add-product', 'addProduct')->name('campaign.add-product');
            Route::delete('campaign/remove-product/{id}', 'removeProduct')->name('campaign.remove-product');
        });
        Route::resource('campaign', CampaignController::class);
    });
});
Route::prefix('employee')->name('employee.')->middleware(['auth', 'employee.access'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/start', [AttendanceController::class, 'startAttendance'])->name('attendance.start');
    Route::post('/attendance/end', [AttendanceController::class, 'endAttendance'])->name('attendance.end');
    Route::get('/attendance/status', [AttendanceController::class, 'attendanceStatus'])->name('attendance.status');
    Route::get('{employee}/summary', [AttendanceController::class, 'summary'])
        ->name('summary');
});




// Route::get('/{any}', function () {
//     return view('welcome'); // resources/views/app.blade.php
// })->where('any', '^(?!admin).*');



// Serve uploaded images with CORS

// require __DIR__ . '/auth.php';

Gate::before(function ($user, $ability) {
    return $user->hasRole('SuperAdmin') ? true : null;
});

// Route::get('/{any}', function () {
//     return view('welcome');
// })->where('any', '^(?!admin|admin/login|forgot-password).*$');

// Route::get('/{any}', function () {
//     return view('welcome');
// })->where('any', '^(?!api|admin|employee|sanctum|admin/login|logout|password|register|_ignition|horizon).*');
