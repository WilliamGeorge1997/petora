<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Api\OrderMethodController;
use Modules\Order\Http\Controllers\Api\OrderStatusController;
use Modules\Order\Http\Controllers\Api\PaymentMethodController;
use Modules\Order\Http\Controllers\OrderController;

Route::get('order-methods', [OrderMethodController::class, 'index']);
Route::get('payment-methods', [PaymentMethodController::class, 'index']);
Route::get('order-statuses', [OrderStatusController::class, 'index']);

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('orders', OrderController::class)->names('order');
});
