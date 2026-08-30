<?php

use Illuminate\Support\Facades\Route;
use Modules\Country\Http\Controllers\Api\CountryController;
use Modules\Country\Http\Controllers\Api\CityController;
use Modules\Country\Http\Controllers\Api\ZoneController;

Route::get('countries', [CountryController::class, 'index']);
Route::get('countries/{country_id}/cities', [CityController::class, 'index']);
Route::get('cities/{city_id}/zones', [ZoneController::class, 'index']);
