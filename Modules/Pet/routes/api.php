<?php

use Illuminate\Support\Facades\Route;
use Modules\Pet\Http\Controllers\Api\PetController;
use Modules\Pet\Http\Controllers\Api\PetTypeController;

    Route::get('pet-types', [PetTypeController::class, 'index']);
    Route::apiResource('pets', PetController::class);

