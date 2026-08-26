<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('categories/{category}/activate', [CategoryController::class, 'activate'])->name('category.activate');
    Route::resource('categories', CategoryController::class)->names('category');
});
