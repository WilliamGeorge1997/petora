<?php

use Illuminate\Support\Facades\Route;
use Modules\Doctor\Http\Controllers\DoctorController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('doctors', DoctorController::class)->names('doctor');
});
