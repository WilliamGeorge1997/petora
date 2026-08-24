<?php

use Illuminate\Support\Facades\Route;
use Modules\Common\Http\Controllers\CommonController;
use Modules\Common\Http\Controllers\LanguageController;


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('lang/{locale}', LanguageController::class)->name('lang.switch');
    Route::get('/settings', [CommonController::class, 'index'])->name('settings.index');
    Route::post('/settings', [CommonController::class, 'store'])->name('settings.store');
    Route::get('logs', [CommonController::class, 'logs'])->name('logs.index');
});
