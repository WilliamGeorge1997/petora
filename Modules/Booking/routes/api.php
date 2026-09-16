<?php

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Controllers\Api\BookingStatusController;

Route::get('booking-statuses', [BookingStatusController::class, 'index']);