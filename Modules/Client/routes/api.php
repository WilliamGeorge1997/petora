<?php

use Illuminate\Support\Facades\Route;
use Modules\Client\Http\Controllers\Api\AddressController;
use Modules\Client\Http\Controllers\Api\ClientAuthController;
use Modules\Client\Http\Controllers\Api\ClientController;

Route::prefix('auth')->group(function () {
    Route::post('register', [ClientAuthController::class, 'register']);
    Route::post('login', [ClientAuthController::class, 'login']);
    Route::post('verify', [ClientAuthController::class, 'verify']);
    Route::post('logout', [ClientAuthController::class, 'logout']);
    Route::get('me', [ClientAuthController::class, 'me']);


    Route::post('forget-password', [ClientAuthController::class, 'forgetPassword']);
    Route::post('verify-forget-password', [ClientAuthController::class, 'verifyForgetPassword']);
    Route::post('new-password', [ClientAuthController::class, 'newPassword']);

    Route::post('edit-profile', [ClientController::class, 'editProfile']);
});

Route::apiResource('addresses', AddressController::class);
Route::put('addresses/{address}/default', [AddressController::class, 'default']);
