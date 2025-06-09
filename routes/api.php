<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\OtpController;

// Auth Routes
Route::controller(AuthController::class)->group(function () {
    // Register
    Route::post('/register', [AuthController::class, 'register']);
    // Login
    Route::post('/login', [AuthController::class, 'login']);
    // Log out
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// OTP Routes
Route::controller(OtpController::class)->group(function () {
    // Send OTP to verify Email
    Route::post('/send-otp-email-verify', [OtpController::class, 'sendEmailVerificationOtp'])
        ->middleware('auth:sanctum');
    // Verify Email
    Route::post('/verify-email', [OtpController::class, 'verifyEmail'])
        ->middleware('auth:sanctum');
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
