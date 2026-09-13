<?php

use Illuminate\Support\Facades\Route;
use Modules\Driver\Http\Controllers\Api\DriverAuthController;

Route::prefix('driver/auth')->group(function () {
    Route::post('login', [DriverAuthController::class, 'login']);
    Route::post('logout', [DriverAuthController::class, 'logout']);
    Route::get('me', [DriverAuthController::class, 'me']);
    Route::post('availability', [DriverAuthController::class, 'changeAvailability']);
    Route::post('un-active', [DriverAuthController::class, 'unActive']);
    Route::post('unActive', [DriverAuthController::class, 'unActive']);
    Route::post('change-password', [DriverAuthController::class, 'changePassword']);
    Route::post('changePassword', [DriverAuthController::class, 'changePassword']);
});
