<?php

use Illuminate\Support\Facades\Route;
use Modules\Clinic\Http\Controllers\Api\ClinicController;


Route::apiResource('clinics', ClinicController::class)->only(['index', 'show']);
