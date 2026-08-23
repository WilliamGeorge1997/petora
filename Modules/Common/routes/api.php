<?php

use Illuminate\Support\Facades\Route;
use Modules\Common\Http\Controllers\Api\CommonController;

Route::get('/terms/{lang}', [CommonController::class, 'terms']);
Route::get('/privacy/{lang}', [CommonController::class, 'privacy']);
Route::get('/about/{lang}', [CommonController::class, 'about']);
Route::get('/tax', [CommonController::class, 'tax']);
Route::get('/social-links', [CommonController::class, 'socialLinks']);
