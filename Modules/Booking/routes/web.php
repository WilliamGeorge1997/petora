<?php

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Controllers\BookingController;
use Modules\Booking\Http\Controllers\BookingStatusController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('booking-statuses/{booking_status}/activate', [BookingStatusController::class, 'activate'])->name('booking_status.activate');
    Route::resource('booking-statuses', BookingStatusController::class)->names('booking_status')->except(['show']);

    Route::resource('bookings', BookingController::class)->names('booking');
});
