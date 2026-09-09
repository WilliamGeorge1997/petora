<?php

use Illuminate\Support\Facades\Route;
use Modules\Clinic\Http\Controllers\ClinicController;
use Modules\Clinic\Http\Controllers\ClinicDeliveryScheduleController;
use Modules\Clinic\Http\Controllers\ClinicProductController;
use Modules\Clinic\Http\Controllers\ClinicServiceController;
use Modules\Clinic\Http\Controllers\ClinicServiceScheduleController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('clinics/{clinic}/activate', [ClinicController::class, 'activate'])->name('clinic.activate');

    // Clinic Products Routes
    Route::get('clinics/{clinic}/products', [ClinicProductController::class, 'index'])->name('clinic.products.index');
    Route::get('clinics/{clinic}/products/export', [ClinicProductController::class, 'export'])->name('clinic.products.export');
    Route::post('clinics/{clinic}/products/import', [ClinicProductController::class, 'import'])->name('clinic.products.import');

    // Clinic Services Routes
    Route::get('clinics/{clinic}/services', [ClinicServiceController::class, 'index'])->name('clinic.services.index');
    Route::get('clinics/{clinic}/services/export', [ClinicServiceController::class, 'export'])->name('clinic.services.export');
    Route::post('clinics/{clinic}/services/import', [ClinicServiceController::class, 'import'])->name('clinic.services.import');
    Route::post('clinics/{clinic}/services/import-all', [ClinicServiceController::class, 'importAll'])->name('clinic.services.import-all');
    Route::put('clinics/{clinic}/services/{clinic_service}', [ClinicServiceController::class, 'update'])->name('clinic.services.update');
    Route::patch('clinics/{clinic}/services/{clinic_service}/activate', [ClinicServiceController::class, 'activate'])->name('clinic.services.activate');
    Route::delete('clinics/{clinic}/services/{clinic_service}', [ClinicServiceController::class, 'destroy'])->name('clinic.services.destroy');

    // Clinic Service Schedules Routes
    Route::get('clinics/{clinic}/services/{clinic_service}/schedules', [ClinicServiceScheduleController::class, 'index'])->name('clinic.services.schedules.index');
    Route::get('clinics/{clinic}/services/{clinic_service}/schedules/create', [ClinicServiceScheduleController::class, 'create'])->name('clinic.services.schedules.create');
    Route::post('clinics/{clinic}/services/{clinic_service}/schedules', [ClinicServiceScheduleController::class, 'store'])->name('clinic.services.schedules.store');
    Route::get('clinics/{clinic}/services/{clinic_service}/schedules/{schedule_id}/edit', [ClinicServiceScheduleController::class, 'edit'])->name('clinic.services.schedules.edit');
    Route::put('clinics/{clinic}/services/{clinic_service}/schedules/{schedule_id}', [ClinicServiceScheduleController::class, 'update'])->name('clinic.services.schedules.update');
    Route::delete('clinics/{clinic}/services/{clinic_service}/schedules/{schedule}', [ClinicServiceScheduleController::class, 'destroy'])->name('clinic.services.schedules.destroy');

    // Clinic Delivery Schedules Routes
    Route::get('clinics/{clinic}/delivery-schedules', [ClinicDeliveryScheduleController::class, 'index'])->name('clinic.delivery-schedules.index');
    Route::get('clinics/{clinic}/delivery-schedules/create', [ClinicDeliveryScheduleController::class, 'create'])->name('clinic.delivery-schedules.create');
    Route::post('clinics/{clinic}/delivery-schedules', [ClinicDeliveryScheduleController::class, 'store'])->name('clinic.delivery-schedules.store');
    Route::get('clinics/{clinic}/delivery-schedules/{schedule_id}/edit', [ClinicDeliveryScheduleController::class, 'edit'])->name('clinic.delivery-schedules.edit');
    Route::put('clinics/{clinic}/delivery-schedules/{schedule_id}', [ClinicDeliveryScheduleController::class, 'update'])->name('clinic.delivery-schedules.update');
    Route::delete('clinics/{clinic}/delivery-schedules/{schedule}', [ClinicDeliveryScheduleController::class, 'destroy'])->name('clinic.delivery-schedules.destroy');

    Route::get('clinics/{clinic_id}/edit', [ClinicController::class, 'edit'])->name('clinic.edit');
    Route::resource('clinics', ClinicController::class)->names('clinic')->except(['show', 'edit']);
});
