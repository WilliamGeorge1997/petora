<?php

use Illuminate\Support\Facades\Route;
use Modules\Pet\Http\Controllers\PetController;
use Modules\Pet\Http\Controllers\PetTypeController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Pet Type Routes
    Route::patch('pet-types/{pet_type}/activate', [PetTypeController::class, 'activate'])->name('pet_type.activate');
    Route::resource('pet-types', PetTypeController::class)->names('pet_type');

    // Pet Routes
    Route::resource('pets', PetController::class)->names('pet');
});
