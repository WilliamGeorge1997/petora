<?php

use Illuminate\Support\Facades\Route;
use Modules\Clinic\Http\Controllers\ClinicController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('clinics/{clinic}/activate', [ClinicController::class, 'activate'])->name('clinic.activate');

    // Clinic Products Routes
    Route::get('clinics/{clinic}/products', [\Modules\Clinic\Http\Controllers\ClinicProductController::class, 'index'])->name('clinic.products.index');
    Route::get('clinics/{clinic}/products/export', [\Modules\Clinic\Http\Controllers\ClinicProductController::class, 'export'])->name('clinic.products.export');
    Route::post('clinics/{clinic}/products/import', [\Modules\Clinic\Http\Controllers\ClinicProductController::class, 'import'])->name('clinic.products.import');

    Route::resource('clinics', ClinicController::class)->names('clinic');
});
