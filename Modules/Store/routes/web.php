<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\StoreController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('stores/{store}/activate', [StoreController::class, 'activate'])->name('store.activate');
    Route::resource('stores', StoreController::class)->names('store');
});
