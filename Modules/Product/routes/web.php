<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('product/{product}/activate', [ProductController::class, 'activate'])->name('product.activate');
    Route::resource('products', ProductController::class)->names('product');
});
