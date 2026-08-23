<?php

use Illuminate\Support\Facades\Route;
use Modules\Common\Http\Controllers\CommonController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/settings', [CommonController::class, 'index'])->name('settings.index');
    Route::post('/settings', [CommonController::class, 'store'])->name('settings.store');
    Route::get('logs', [CommonController::class, 'logs'])->name('logs.index');
});
