<?php

use App\Http\Controllers\API\Customer\PaymentController;
use App\Http\Controllers\API\Customer\PaypalPaymentController;
use App\Http\Controllers\API\Customer\StripePaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// ========================================     Stripe success & Cancel Routes
Route::prefix('payment/stripe')
    ->controller(StripePaymentController::class)
    ->group(function () {
        Route::get('success', 'handleStripeSuccess')->name('stripe.success');
        Route::get('cancel', 'handleStripeCancel')->name('stripe.cancel');
    });



// ========================================     Stripe success & Cancel Routes
Route::prefix('payment/paypal')
    ->controller(PaypalPaymentController::class)
    ->group(function () {
        Route::get('success', 'handlePaypalSuccess')->name('paypal.success');
        Route::get('cancel', 'handlePaypalCancel')->name('paypal.cancel');
    });