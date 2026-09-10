<?php

use Illuminate\Support\Facades\Route;
use Modules\Country\Http\Controllers\CityController;
use Modules\Country\Http\Controllers\CountryController;
use Modules\Country\Http\Controllers\ZoneController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Ajax
    Route::prefix('ajax')->name('ajax.')->group(function () {
        Route::get('cities', [CityController::class, 'ajax'])->name('cities');
        Route::get('zones', [ZoneController::class, 'ajax'])->name('zones');
    });

    // Country
    Route::resource('countries', CountryController::class)->except(['show']);
    Route::patch('countries/{country}/activate', [CountryController::class, 'activate'])->name('countries.activate');

    // City
    Route::resource('cities', CityController::class)->except(['show']);
    Route::patch('cities/{city}/activate', [CityController::class, 'activate'])->name('cities.activate');

    // Zone
    Route::resource('zones', ZoneController::class)->except(['show']);
    Route::patch('zones/{zone}/activate', [ZoneController::class, 'activate'])->name('zones.activate');
});
