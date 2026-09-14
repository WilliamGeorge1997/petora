<?php

use Illuminate\Support\Facades\Route;
use Modules\Coupon\Http\Controllers\CouponController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('coupons', CouponController::class)->names('coupon');
});
