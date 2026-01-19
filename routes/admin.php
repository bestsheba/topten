<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\PathaoController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MarketingController;
use App\Http\Controllers\Admin\SteadfastController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CustomPageController;
use App\Http\Controllers\Admin\OfferSliderController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\AffiliateUserController;
use App\Http\Controllers\Admin\CustomPageGroupController;
use App\Http\Controllers\Admin\IncompleteOrderController;
use App\Http\Controllers\Admin\AffiliateWithdrawalController;
use App\Http\Controllers\Admin\LandingPageController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GarmentTypeController;
use App\Http\Controllers\MeasurementFieldController;
use App\Http\Controllers\MeasurementProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TailorController;
use App\Http\Controllers\TailorOrderController;

// Admin Auth Route
Route::prefix('admin')->as('admin.')->middleware('auth:admin')->group(function () {

    Route::resource('roles', RoleManagementController::class);
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::controller(SettingsController::class)->group(function () {
        Route::get('/settings/{page}', 'settings')->name('settings');
        Route::put('/settings/update', 'updateSettings')->name('settings.update');
        Route::get('/profile', 'profile')->name('profile');
        Route::put('/update/profile', 'updateProfile')->name('profile.update');
        Route::put('/update/donate-info', 'updateDonateInfo')->name('donate-info.update');
        Route::put('/flash/deal/time', 'flashDealTimer')->name('flash.deal.time');
        Route::put('/site-color', 'siteColor')->name('site.color');
    });
    Route::resource('expense-types', ExpenseCategoryController::class);
    Route::resource('garment-types', GarmentTypeController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('measurement-fields', MeasurementFieldController::class)
        ->except(['create', 'edit', 'show']);
    Route::resource('measurement-profiles', MeasurementProfileController::class)
        ->except(['create', 'edit', 'show', 'update']);

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::resource('custom-page', CustomPageController::class);
    Route::post('custom-page/reorder', [CustomPageController::class, 'reorder'])->name('custom-page.reorder');
    Route::resource('custom-page-group', CustomPageGroupController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('custom-page-group/reorder', [CustomPageGroupController::class, 'reorder'])->name('custom-page-group.reorder');
    Route::resource('category', CategoryController::class);
    Route::resource('services', ServiceController::class);
    Route::get('/get-subcategories', [ProductController::class, 'getSubcategories'])->name('getSubcategories');
    Route::resource('sub-category', SubCategoryController::class);
    Route::resource('brand', BrandController::class);
    Route::resource('product', ProductController::class);
    Route::post('product/offer/{product}', [ProductController::class, 'offerUpdate'])->name('product.offer.update');
    Route::resource('notice', NoticeController::class);
    Route::resource('order', OrderController::class);
    Route::delete('orders/bulk-delete', [OrderController::class, 'bulkDelete'])->name('order.bulk-delete');
    Route::post('orders/bulk-status-update', [OrderController::class, 'bulkStatusUpdate'])->name('order.bulk-status-update');
    Route::post('orders/payment/store', [OrderController::class, 'storePayment'])->name('order.payment.store');
    Route::resource('sliders', SliderController::class);
    Route::resource('offer-slider', OfferSliderController::class);
    Route::get('order/download/invoice/{order}', [OrderController::class, 'download'])->name('invoice.download');
    Route::resource('gallery', GalleryController::class);
    Route::resource('blog', BlogController::class);
    Route::resource('coupon', CouponController::class);
    // Route::resource('testimonial', TestimonialController::class);
    Route::resource('landing-pages', LandingPageController::class);
    Route::get('pos', [OrderController::class, 'pos'])->name('pos');
    Route::get('setup-seo', [MarketingController::class, 'setupSeo'])->name('setup-seo');
    Route::put('update-seo', [MarketingController::class, 'updateSeo'])->name('update-seo');
    Route::get('meta-pixel', [MarketingController::class, 'metaPixel'])->name('meta-pixel');
    Route::put('update-meta-pixel', [MarketingController::class, 'updateMetaPixel'])->name('update-meta-pixel');
    Route::get('google-tag-manager', [MarketingController::class, 'googleTagManager'])->name('google-tag-manager');
    Route::put('update-google-tag-manager', [MarketingController::class, 'updateGoogleTagManager'])->name('update-google-tag-manager');

    // incomplete order
    Route::get('incomplete/order', [IncompleteOrderController::class, 'index'])->name('order.incomplete');
    Route::delete('incomplete/order/delete/{order}', [IncompleteOrderController::class, 'delete'])->name('order.incomplete.delete');
    Route::get('place/order/from/incomplete/{order}', [IncompleteOrderController::class, 'placeOrderForm'])->name('order.incomplete.place');
    Route::post('place/order/from/incomplete/{order}', [IncompleteOrderController::class, 'placeOrder'])->name('order.incomplete.place-order');

    Route::resource('role', RoleController::class);
    Route::resource('admin', AdminController::class);
    Route::middleware(['affiliate.enabled'])->group(function () {
        Route::resource('affiliate-withdrawal', AffiliateWithdrawalController::class)->except(['create', 'store']);
    });
    Route::resource('attributes', AttributeController::class)->except(['create', 'edit', 'show']);
    Route::get('/affiliate-users', [AffiliateUserController::class, 'index'])->name('affiliate-users.index');

    // Pathao routes
    Route::prefix('pathao')->as('pathao.')->group(function () {
        Route::get('/settings', [PathaoController::class, 'settings'])->name('settings');
        Route::put('/settings', [PathaoController::class, 'updateSettings'])->name('settings.update');
        Route::post('/test-connection', [PathaoController::class, 'testConnection'])->name('test-connection');
        Route::post('/create-store', [PathaoController::class, 'createStore'])->name('create-store');
        Route::post('/update-store-id', [PathaoController::class, 'updateStoreId'])->name('update-store-id');
        Route::get('/fetch-stores', [PathaoController::class, 'fetchStores'])->name('fetch-stores');
        Route::get('/sync-store', [PathaoController::class, 'syncStore'])->name('sync-store');
        Route::get('/orders', [PathaoController::class, 'orders'])->name('orders');
        Route::post('/create-single-order', [PathaoController::class, 'createSingleOrder'])->name('create-single-order');
        Route::post('/create-bulk-orders', [PathaoController::class, 'createBulkOrders'])->name('create-bulk-orders');
        Route::get('/cities', [PathaoController::class, 'getCities'])->name('cities');
        Route::get('/zones', [PathaoController::class, 'getZones'])->name('zones');
        Route::get('/areas', [PathaoController::class, 'getAreas'])->name('areas');
        Route::post('/calculate-price', [PathaoController::class, 'calculatePrice'])->name('calculate-price');
        Route::post('/orders/{order}/sync-status', [PathaoController::class, 'syncStatus'])->name('orders.sync-status');
    });

    // Steadfast routes (Steadfast Courier integration)
    Route::prefix('steadfast')->as('steadfast.')->group(function () {
        Route::get('/settings', [SteadfastController::class, 'settings'])->name('settings');
        Route::put('/settings', [SteadfastController::class, 'updateSettings'])->name('settings.update');
        Route::post('/test-connection', [SteadfastController::class, 'testConnection'])->name('test-connection');
        Route::get('/orders', [SteadfastController::class, 'orders'])->name('orders');
        Route::post('/create-single-order', [SteadfastController::class, 'createSingleOrder'])->name('create-single-order');
        Route::post('/create-bulk-orders', [SteadfastController::class, 'createBulkOrders'])->name('create-bulk-orders');
        Route::post('/orders/{order}/sync-status', [SteadfastController::class, 'syncStatus'])->name('orders.sync-status');
    });
});

// Admin Auth Route
Route::prefix('admin')->as('admin.')->controller(LoginController::class)->group(function () {
    Route::get('/login', 'create')->name('login');
    Route::post('/login', 'store')->name('login');
});

Route::prefix('admin')->as('admin.')->middleware('auth:admin')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::resource('tailors', TailorController::class);
});
Route::delete('attribute-values/{value}', [AttributeController::class, 'destroyValue'])
    ->name('attribute-values.destroy');
Route::prefix('admin/products/{product}')->middleware('auth:admin')->group(function () {
    Route::post('generate-variations', [ProductVariantController::class, 'generateVariations'])
        ->name('products.variations.generate');
    Route::post('store-variations', [ProductVariantController::class, 'storeVariations'])
        ->name('products.variations.store');
    Route::put('update-variations', [ProductVariantController::class, 'updateVariations'])
        ->name('products.variations.update');
    Route::get('variations/{variation}', [ProductVariantController::class, 'destroyVariation'])
        ->name('products.variations.destroy');
});

Route::get('/tailor/orders/create', [TailorOrderController::class, 'create']);
Route::get('/tailor/measurements/{id}', [TailorOrderController::class, 'measurements']);
Route::post('/tailor/orders', [TailorOrderController::class, 'store']);
