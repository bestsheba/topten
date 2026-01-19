<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NavController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\AffiliateController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\LandingViewController;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/product/{slug}', 'productDetails')->name('product.details');
    Route::get('/shop', 'shop')->name('shop');
    Route::get('/page/{slug}', 'customPage')->name('custom.page');
    Route::get('/search-autocomplete', 'searchAutoComplete');
});

Route::get('/landing/{url}', [LandingViewController::class, 'landingPage'])->name('landing.page.view');
Route::post('/landing/{url}/order', [LandingViewController::class, 'storeOrder'])->name('landing.page.order');

// Public order tracking (no authentication required)
Route::get('/track-order', [DashboardController::class, 'publicOrderTrack'])
    ->name('track.order');
Route::get('/navigation', [NavController::class, 'getNavigation']);
Route::middleware(['auth', 'verified'])->as('user.')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/my-account', 'myAccount')->name('my-account');
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/profile', 'profileUpdate')->name('profile.update');
        Route::get('/menu', 'menu')->name('account.menu');
        
        Route::get('/password', 'password')->name('password');
        Route::post('/password', 'passwordUpdate')->name('password.update');
        Route::get('/orders', 'order')->name('order');
        Route::get('/order/show/{order}', 'orderShow')->name('order.show');
        Route::get('/order/track', 'orderTrack')->name('order.track');
        
        Route::get('/wishlist', 'wishlist')->name('wishlist');
        Route::post('/wishlist', 'toggleWishlist')->name('toggle.wishlist');
        
        Route::get('notification', 'notification')->name('notification');
        Route::get('address', 'address')->name('address');
        Route::post('address', 'addressStore')->name('address');
        Route::get('transaction', 'transaction')->name('transaction');
        Route::get('offer', 'offer')->name('offer')->withoutMiddleware(['auth', 'verified']);
        Route::get('referral', 'referral')->name('referral');
        Route::get('tips', 'tips')->name('tips')->withoutMiddleware(['auth', 'verified']);
        Route::get('rate-us', 'rateUs')->name('rate-us');
        Route::post('rate-us', 'rateUsStore')->name('rate-us');
    });
});
Route::get('/foo', function () {
    Artisan::call('storage:link');
});
Route::controller(CartController::class)->group(function () {
    Route::post('/add-to-cart', 'add')->name('add.to.cart');
    Route::get('/cart', 'cart')->name('cart');
    Route::post('/cart', 'changeCartQty')->name('change.cart.qty');
    Route::get('/checkout', 'checkout')->name('checkout');
    Route::post('/place-order', 'placeOrder')->name('place.order');
    Route::get('/order-success/{order}', 'orderSuccess')->name('order.success');
    Route::post('/add-delivery-charge', 'setDeliveryCharge')->name('set.delivery-charge');
    Route::post('apply/coupon', 'applyCoupon')->name('apply.coupon.code');
    Route::post('remove/coupon', 'removeCoupon')->name('remove.coupon');
    Route::post('push-incomplete-order-data/{incomplete_order}', 'addIncompleteOrderData')->name('push.incomplete.order.data');
    Route::post('/cart/remove', 'remove')->name('cart.remove');
});
// Affiliate routes
Route::middleware(['affiliate.enabled'])->group(function () {
    Route::get('/affiliate/register', [AffiliateController::class, 'showRegister'])->name('affiliate.register');
    Route::post('/affiliate/register', [AffiliateController::class, 'register'])->name('affiliate.register.post');
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/affiliate/wallet', [AffiliateController::class, 'wallet'])->name('affiliate.wallet');
        Route::post('/affiliate/withdraw', [AffiliateController::class, 'requestWithdrawal'])->name('affiliate.withdraw');
        Route::get('/affiliate/earnings', [AffiliateController::class, 'earnings'])->name('affiliate.earnings');
        Route::get('/affiliate/dashboard', [DashboardController::class, 'affiliateDashboard'])->name('affiliate.dashboard');
    });
});

// Payment Routes
Route::group(['prefix' => 'payment'], function () {
    Route::post('success', [App\Http\Controllers\Frontend\PaymentController::class, 'success'])->name('payment.success');
    Route::post('failed', [App\Http\Controllers\Frontend\PaymentController::class, 'failed'])->name('payment.failed');
    Route::post('cancel', [App\Http\Controllers\Frontend\PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Stripe Payment Routes
Route::group(['prefix' => 'stripe'], function () {
    Route::get('success', [App\Http\Controllers\Frontend\StripePaymentController::class, 'success'])->name('stripe.success');
    Route::get('cancel', [App\Http\Controllers\Frontend\StripePaymentController::class, 'cancel'])->name('stripe.cancel');
});

Route::get('products/{product}/details', [ProductController::class, 'details'])
    ->name('products.details');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/review/submit', [ReviewController::class, 'store'])
    ->name('review.submit')
    ->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/otp/verify', [GoogleAuthController::class, 'showOtpVerify'])->name('otp.verify');
    Route::post('/otp/verify', [GoogleAuthController::class, 'verifyOtp'])->name('otp.verify.submit');
    Route::post('/otp/resend', [GoogleAuthController::class, 'resendOtp'])->name('otp.resend');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
