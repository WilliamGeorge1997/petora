<?php

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Controllers\Api\BookingStatusController;
use Modules\Booking\Http\Controllers\Api\BookingController;

Route::get('booking-statuses', [BookingStatusController::class, 'index']);

Route::resource('bookings', BookingController::class)->only(['index', 'store']);