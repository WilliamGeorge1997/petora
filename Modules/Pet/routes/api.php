<?php

use Illuminate\Support\Facades\Route;
use Modules\Pet\Http\Controllers\Api\PetController;
use Modules\Pet\Http\Controllers\Api\PetTypeController;

    Route::get('pet-types', [PetTypeController::class, 'index']);
    Route::apiResource('pets', PetController::class);

    Route::apiResource('pets', PetController::class)->except(['show', 'update']);
    Route::get('pets/{pet_id}', [PetController::class, 'show']);
    Route::post('pets/{pet}', [PetController::class, 'update']);
 

