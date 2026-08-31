<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\Api\StoreController;


Route::get('stores', [StoreController::class, 'index']);
