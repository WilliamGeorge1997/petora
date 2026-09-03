<?php

use Illuminate\Support\Facades\Route;
use Modules\Service\Http\Controllers\ServiceController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('services/{service}/activate', [ServiceController::class, 'activate'])->name('service.activate');
    Route::resource('services', ServiceController::class)->names('service');
});
