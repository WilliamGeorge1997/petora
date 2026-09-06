<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('payment-methods/{payment_method}/activate', [\Modules\Order\Http\Controllers\PaymentMethodController::class, 'activate'])->name('payment_method.activate');
    Route::resource('payment-methods', \Modules\Order\Http\Controllers\PaymentMethodController::class)->names('payment_method')->except(['show']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('orders', OrderController::class)->names('order');
});
