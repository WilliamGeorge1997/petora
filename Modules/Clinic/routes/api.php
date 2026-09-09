<?php

use Illuminate\Support\Facades\Route;
use Modules\Clinic\Http\Controllers\Api\ClinicController;
use Modules\Clinic\Http\Controllers\Api\ClinicDeliveryScheduleController;
use Modules\Clinic\Http\Controllers\Api\ClinicServiceController;

Route::apiResource('clinics', ClinicController::class)->only(['index', 'show']);
Route::get('clinics/{clinic}/delivery-schedules', [ClinicDeliveryScheduleController::class, 'index']);

Route::get('clinic-services/{clinic_service_id}', [ClinicServiceController::class, 'show']);
