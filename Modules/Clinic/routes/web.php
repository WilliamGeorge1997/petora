<?php

use Illuminate\Support\Facades\Route;
use Modules\Clinic\Http\Controllers\ClinicController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('clinics/{clinic}/activate', [ClinicController::class, 'activate'])->name('clinic.activate');

    // Clinic Products Routes
    Route::get('clinics/{clinic}/products', [\Modules\Clinic\Http\Controllers\ClinicProductController::class, 'index'])->name('clinic.products.index');
    Route::get('clinics/{clinic}/products/export', [\Modules\Clinic\Http\Controllers\ClinicProductController::class, 'export'])->name('clinic.products.export');
    Route::post('clinics/{clinic}/products/import', [\Modules\Clinic\Http\Controllers\ClinicProductController::class, 'import'])->name('clinic.products.import');

    // Clinic Services Routes
    Route::get('clinics/{clinic}/services', [\Modules\Clinic\Http\Controllers\ClinicServiceController::class, 'index'])->name('clinic.services.index');
    Route::get('clinics/{clinic}/services/export', [\Modules\Clinic\Http\Controllers\ClinicServiceController::class, 'export'])->name('clinic.services.export');
    Route::post('clinics/{clinic}/services/import', [\Modules\Clinic\Http\Controllers\ClinicServiceController::class, 'import'])->name('clinic.services.import');
    Route::post('clinics/{clinic}/services/import-all', [\Modules\Clinic\Http\Controllers\ClinicServiceController::class, 'importAll'])->name('clinic.services.import-all');
    Route::put('clinics/{clinic}/services/{clinicService}', [\Modules\Clinic\Http\Controllers\ClinicServiceController::class, 'update'])->name('clinic.services.update');
    Route::patch('clinics/{clinic}/services/{clinicService}/activate', [\Modules\Clinic\Http\Controllers\ClinicServiceController::class, 'activate'])->name('clinic.services.activate');
    Route::delete('clinics/{clinic}/services/{clinicService}', [\Modules\Clinic\Http\Controllers\ClinicServiceController::class, 'destroy'])->name('clinic.services.destroy');

    Route::resource('clinics', ClinicController::class)->names('clinic');
});
