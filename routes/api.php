<?php

use App\Http\Controllers\API\V1\SocialLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\LoginController;
use App\Http\Controllers\API\V1\RegisterController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\FlashDealController;
use App\Http\Controllers\API\V1\SliderController;
use App\Http\Controllers\API\V1\ForgetPasswordController;
use App\Http\Controllers\API\V1\ProfileController;
use App\Http\Controllers\API\V1\DeliveryChargeController;
use App\Http\Controllers\API\V1\PaymentMethodController;
use App\Http\Controllers\API\V1\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/login', [LoginController::class, 'store']);
    Route::post('/social-login', [SocialLoginController::class, 'socialLogin']);

    // Public product routes
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    // Product search route

    // Public category routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/categories/{categoryId}/products', [CategoryController::class, 'products']);

    // Flash deal route
    Route::get('/flash-deals', [FlashDealController::class, 'index']);

    // Sliders route
    Route::get('/sliders', [SliderController::class, 'index']);

    // Forget password route
    Route::post('/forget-password', [ForgetPasswordController::class, 'forgetPassword']);

    // Delivery charges route
    Route::get('/delivery-charges', [DeliveryChargeController::class, 'getDeliveryCharges']);

    // Payment methods route
    Route::get('/payment-methods', [PaymentMethodController::class, 'index']);
});

Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy']);
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    // Order routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    // Place order route moved to authenticated group
    Route::post('/place-order', [OrderController::class, 'placeOrder']);

    Route::post('/otp/verify', [SocialLoginController::class, 'verifyOtp']);
    Route::post('/otp/resend', [SocialLoginController::class, 'resendOtp']);
});