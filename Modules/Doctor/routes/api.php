<?php

use Illuminate\Support\Facades\Route;
use Modules\Doctor\Http\Controllers\Api\DoctorController;

Route::get('clinics/{clinic_id}/doctors', [DoctorController::class, 'index']);
