<?php

use Illuminate\Http\Request;
use Modules\Common\Http\Controllers\api\CommonController;
use Modules\Common\Http\Controllers\api\ReviewController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/terms/{lang}', [CommonController::class, 'terms']);

Route::get('/about/{lang}', [CommonController::class, 'about']);

Route::post('/contactUs', [CommonController::class, 'contactUs']);

Route::get('/tax', [CommonController::class, 'tax']);

Route::get('currency', [CommonController::class, 'currency']);
Route::get('social-links', [CommonController::class, 'socialLinks']);
Route::post('/reviews', [ReviewController::class, 'store']);
