<?php

use Illuminate\Support\Facades\Route;
use Modules\Client\Http\Controllers\ClientController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('clients/{client_id}/activate', [ClientController::class, 'activate'])->name('client.activate');
    Route::resource('clients', ClientController::class)->names('client');
});
