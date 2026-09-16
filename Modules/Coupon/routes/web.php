<?php

use Illuminate\Support\Facades\Route;
use Modules\Coupon\Http\Controllers\CouponController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('coupons/{coupon}/activate', [CouponController::class, 'activate'])->name('coupon.activate');
    Route::resource('coupons', CouponController::class)->names('coupon')->except(['show']);
});
