<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\Api\CategoryController as ApiCategoryController;

Route::prefix('v1')->name('api.')->group(function () {
    Route::get('categories', [ApiCategoryController::class, 'index'])->name('category.index');
});
