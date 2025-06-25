<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\OtpController;
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
// ==============================   Vendor Routes End   ============================== //



// ==============================   Products Routes   ============================== //
Route::controller(ProductController::class)
    ->middleware(['auth:sanctum', 'role:vendor'])
    ->prefix('vendor')
    ->group(function () {
        Route::get('/products', 'index');
        Route::post('/products', 'store');
        Route::get('/products/{id}', 'show');
        Route::put('/products/{id}', 'update');
        Route::delete('/products/{id}', 'destroy');
    });
// ==============================   Products Routes End   ============================== //
