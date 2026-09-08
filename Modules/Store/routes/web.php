<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\StoreController;
use Modules\Store\Http\Controllers\StoreProductController;


Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('stores/{store}/activate', [StoreController::class, 'activate'])->name('store.activate');
    
    // Store Products Routes
    Route::get('stores/{store}/products', [StoreProductController::class, 'index'])->name('store.products.index');
    Route::get('stores/{store}/products/export', [StoreProductController::class, 'export'])->name('store.products.export');
    Route::post('stores/{store}/products/import', [StoreProductController::class, 'import'])->name('store.products.import');

    Route::get('stores/{store_id}/edit', [StoreController::class, 'edit'])->name('store.edit');
    Route::resource('stores', StoreController::class)->names('store')->except(['show', 'edit']);
});
