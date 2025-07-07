<?php

use App\Http\Controllers\API\Customer\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::prefix('payment/stripe')
    ->controller(PaymentController::class)
    ->group(function () {
        Route::get('success', 'handleStripeSuccess')->name('stripe.success');
        Route::get('cancel', 'handleStripeCancel')->name('stripe.cancel');
    });
