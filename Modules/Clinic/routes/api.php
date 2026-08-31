<?php

use Illuminate\Support\Facades\Route;
use Modules\Clinic\Http\Controllers\Api\ClinicController;


Route::get('clinics', [ClinicController::class, 'index']);
