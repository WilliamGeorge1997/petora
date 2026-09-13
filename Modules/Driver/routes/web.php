<?php

use Illuminate\Support\Facades\Route;
use Modules\Driver\Http\Controllers\DriverController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('drivers/{driver}/activate', [DriverController::class, 'activate'])->name('driver.activate');
    Route::resource('drivers', DriverController::class)->names('driver');
});
