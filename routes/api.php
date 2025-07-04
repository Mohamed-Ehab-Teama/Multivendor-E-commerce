<?php

use App\Http\Controllers\API\Admin\CategoryController;
use App\Http\Controllers\API\Customer\CartController;
use App\Http\Controllers\API\Customer\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\OtpController;
use App\Http\Controllers\API\Public\ProductController as PublicProductController;
use App\Http\Controllers\API\Vendor\ProductController;
use App\Http\Controllers\API\Vendor\VendorProfileController;
use Spatie\Permission\Contracts\Role;

// ==============================   Auth Routes     ============================== //
Route::controller(AuthController::class)->group(function () {
    // Register
    Route::post('/register', [AuthController::class, 'register']);
    // Login
    Route::post('/login', [AuthController::class, 'login']);
    // Log out
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});



// ==============================   OTP Routes      ============================== //
Route::controller(OtpController::class)->group(function () {
    // Send OTP to verify Email
    Route::post('/send-otp-email-verify', [OtpController::class, 'sendEmailVerificationOtp'])
        ->middleware('auth:sanctum');

    // Verify Email
    Route::post('/verify-email', [OtpController::class, 'verifyEmail'])
        ->middleware('auth:sanctum');

    // Forget Password
    Route::Post('/forget-password', [OtpController::class, 'sendResetPasswordOtp'])->middleware('auth:sanctum');

    // Reset Password
    Route::post('/reset-password', [OtpController::class, 'resetPassword'])->middleware('auth:sanctum');
});



// ==============================   Vendor Routes   ============================== //
Route::controller(VendorProfileController::class)
    ->middleware(['auth:sanctum', 'role:vendor'])
    ->prefix('vendor')
    ->group(function () {
        // Show Vendor Profile
        Route::get('/profile', 'showVendorProfile');

        // Create or Update Vendor Profile Data
        Route::post('/profile', 'storeOrUpdateVendorProfile');
    });



// ==============================   Products Routes   ============================== //
Route::controller(ProductController::class)
    ->middleware(['auth:sanctum', 'role:vendor'])
    ->prefix('vendor')
    ->group(function () {
        Route::get('/products', 'index');
        Route::post('/products', 'store');
        Route::get('/products/{product}', 'show');
        Route::post('/products/{product}', 'update');
        Route::delete('/products/{product}', 'destroy');
    });



// ==============================   Categories Routes   ============================== //
Route::controller(CategoryController::class)
    ->prefix('admin')
    ->middleware(['auth:sanctum', 'role:admin'])
    ->group(function () {
        Route::get('/categories', 'index');
        Route::post('/categories', 'store');
        Route::get('/categories/{category}', 'show');
        Route::put('/categories/{category}', 'update');
        Route::delete('/categories/{category}', 'destroy');
    });



// ==============================   Public Producrs Routes   ============================== //
Route::controller(PublicProductController::class)
    ->prefix('public')
    ->group(function () {
        Route::get('/products', 'index');
        Route::get('/products/{slug}', 'show');
    });



// ==============================   Public Producrs Routes   ============================== //
Route::controller(CartController::class)
    ->prefix('cart')
    ->middleware(['auth:sanctum', 'role:customer'])
    ->group(function () {
        Route::get('/', 'index');                       // Show Cart items
        Route::post('/add', 'add');                     // Add item
        Route::put('update/{product}', 'update');       // Update Quantity
        Route::delete('/remove/{product}', 'remove');   // Remove item
    });



// ==============================   Public Producrs Routes   ============================== //
Route::controller(OrderController::class)
    ->prefix('orders')
    ->middleware(['auth:sanctum', 'role:customer'])
    ->group(function () {
        Route::get('/orders', 'index');
        Route::get('/orders/{order}', 'show');
        Route::post('/place-order', 'placeOrder');
    });



// 