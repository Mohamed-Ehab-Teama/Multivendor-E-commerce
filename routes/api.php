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
    // Verify Email
    Route::post('/verify-email', [OtpController::class, 'sendOtp']);
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
