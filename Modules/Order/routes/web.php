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

    Route::get('orders/live', [OrderController::class, 'live'])->name('order.live');
    Route::get('orders/live/{order_id}', [OrderController::class, 'liveDetail'])->name('order.live.detail');
    Route::get('orders/{order_id}/edit', [OrderController::class, 'edit'])->name('order.edit');
    Route::resource('orders', OrderController::class)->names('order')->except(['create', 'store', 'show', 'edit']);
});
