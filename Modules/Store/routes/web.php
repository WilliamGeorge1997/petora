<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\StoreController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('stores/{store}/activate', [StoreController::class, 'activate'])->name('store.activate');
    
    // Store Products Routes
    Route::get('stores/{store}/products', [\Modules\Store\Http\Controllers\StoreProductController::class, 'index'])->name('store.products.index');
    Route::get('stores/{store}/products/export', [\Modules\Store\Http\Controllers\StoreProductController::class, 'export'])->name('store.products.export');
    Route::post('stores/{store}/products/import', [\Modules\Store\Http\Controllers\StoreProductController::class, 'import'])->name('store.products.import');

    Route::resource('stores', StoreController::class)->names('store');
});
