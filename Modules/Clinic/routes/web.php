<?php

use Illuminate\Support\Facades\Route;
use Modules\Clinic\Http\Controllers\ClinicController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('clinics/{clinic}/activate', [ClinicController::class, 'activate'])->name('clinic.activate');
    Route::resource('clinics', ClinicController::class)->names('clinic');
});
