<?php

use Illuminate\Support\Facades\Route;
use Modules\Company\Http\Controllers\CompanyController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('companies/{company}/activate', [CompanyController::class, 'activate'])->name('company.activate');
    Route::resource('companies', CompanyController::class)->names('company');
});
