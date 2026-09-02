<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Api\ProductController;

Route::get('stores/{seller_id}/categories/{category_id}/products', [ProductController::class, 'index']);
Route::get('clinics/{seller_id}/categories/{category_id}/products', [ProductController::class, 'index']);

Route::get('stores/{seller_id}/products/{product}', [ProductController::class, 'show']);
Route::get('clinics/{seller_id}/products/{product}', [ProductController::class, 'show']);
