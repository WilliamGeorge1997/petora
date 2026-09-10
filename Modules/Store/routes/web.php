<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\StoreController;
use Modules\Store\Http\Controllers\StoreDeliveryScheduleController;
use Modules\Store\Http\Controllers\StoreProductController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('stores/{store}/activate', [StoreController::class, 'activate'])->name('store.activate');

    // Store Products Routes
    Route::get('stores/{store}/products', [StoreProductController::class, 'index'])->name('store.products.index');
    Route::get('stores/{store}/products/export', [StoreProductController::class, 'export'])->name('store.products.export');
    Route::post('stores/{store}/products/import', [StoreProductController::class, 'import'])->name('store.products.import');

    // Store Delivery Schedules Routes
    Route::get('stores/{store}/delivery-schedules', [StoreDeliveryScheduleController::class, 'index'])->name('store.delivery-schedules.index');
    Route::get('stores/{store}/delivery-schedules/create', [StoreDeliveryScheduleController::class, 'create'])->name('store.delivery-schedules.create');
    Route::post('stores/{store}/delivery-schedules', [StoreDeliveryScheduleController::class, 'store'])->name('store.delivery-schedules.store');
    Route::get('stores/{store}/delivery-schedules/{schedule_id}/edit', [StoreDeliveryScheduleController::class, 'edit'])->name('store.delivery-schedules.edit');
    Route::put('stores/{store}/delivery-schedules/{schedule_id}', [StoreDeliveryScheduleController::class, 'update'])->name('store.delivery-schedules.update');
    Route::delete('stores/{store}/delivery-schedules/{schedule}', [StoreDeliveryScheduleController::class, 'destroy'])->name('store.delivery-schedules.destroy');

    Route::get('stores/{store_id}/edit', [StoreController::class, 'edit'])->name('store.edit');
    Route::resource('stores', StoreController::class)->names('store')->except(['show', 'edit']);
});
