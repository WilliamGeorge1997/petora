<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\Api\StoreController;
use Modules\Store\Http\Controllers\Api\StoreDeliveryScheduleController;

Route::get('stores', [StoreController::class, 'index']);
Route::get('stores/{store}/delivery-schedules', [StoreDeliveryScheduleController::class, 'index']);
