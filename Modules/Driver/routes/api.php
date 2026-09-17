<?php

use Illuminate\Support\Facades\Route;
use Modules\Driver\Http\Controllers\Api\DriverAuthController;
use Modules\Driver\Http\Controllers\Api\DriverController;

Route::prefix('driver')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [DriverAuthController::class, 'login']);
        Route::post('logout', [DriverAuthController::class, 'logout']);
        Route::get('me', [DriverAuthController::class, 'me']);
        Route::post('availability', [DriverAuthController::class, 'changeAvailability']);
        Route::post('unActive', [DriverAuthController::class, 'unActive']);
        Route::post('changePassword', [DriverAuthController::class, 'changePassword']);
    });

    //Order
    Route::get('orders/all', [DriverController::class, 'orders']);
    Route::get('orders/new', [DriverController::class, 'newOrders']);
    Route::get('orders/open', [DriverController::class, 'openOrders']);
    Route::get('orders/closed', [DriverController::class, 'closedOrders']);

    //Order Details
    Route::get('order/details/{id}', [DriverController::class, 'orderDetails']);

    //Order Actions
    Route::post('orders/accept', [DriverController::class, 'acceptOrder']);
    Route::post('orders/deliver', [DriverController::class, 'deliverOrder']);
    Route::post('orders/refuse', [DriverController::class, 'refuseOrder']);
    Route::post('orders/fail', [DriverController::class, 'failOrder']);

    Route::post('saveLocation', [DriverController::class, 'saveLocation']);

    Route::post('lang/change', [DriverController::class, 'changeLocale']);
});

