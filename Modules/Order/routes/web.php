<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;
use Modules\Order\Http\Controllers\OrderMethodController;
use Modules\Order\Http\Controllers\OrderStatusController;
use Modules\Order\Http\Controllers\PaymentMethodController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('payment-methods/{payment_method}/activate', [PaymentMethodController::class, 'activate'])->name('payment_method.activate');
    Route::resource('payment-methods', PaymentMethodController::class)->names('payment_method')->except(['show']);

    Route::patch('order-methods/{order_method}/activate', [OrderMethodController::class, 'activate'])->name('order_method.activate');
    Route::resource('order-methods', OrderMethodController::class)->names('order_method')->except(['show']);

    Route::patch('order-statuses/{order_status}/activate', [OrderStatusController::class, 'activate'])->name('order_status.activate');
    Route::resource('order-statuses', OrderStatusController::class)->names('order_status')->except(['show']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('orders', OrderController::class)->names('order');
});
