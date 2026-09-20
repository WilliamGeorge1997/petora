<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Api\OrderMethodController;
use Modules\Order\Http\Controllers\Api\OrderStatusController;
use Modules\Order\Http\Controllers\Api\PaymentMethodController;
use Modules\Order\Http\Controllers\Api\OrderController;

Route::get('order-methods', [OrderMethodController::class, 'index']);
Route::get('payment-methods', [PaymentMethodController::class, 'index']);
Route::get('order-statuses', [OrderStatusController::class, 'index']);

Route::resource('orders', OrderController::class)->only(['index', 'store']);
