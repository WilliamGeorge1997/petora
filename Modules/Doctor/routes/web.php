<?php

use Illuminate\Support\Facades\Route;
use Modules\Doctor\Http\Controllers\DoctorController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('doctors/{doctor}/activate', [DoctorController::class, 'activate'])->name('doctor.activate');
    Route::resource('doctors', DoctorController::class)->names('doctor');
});
