<?php

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

Route::group([
    'prefix' => 'branches',
    'namespace' => 'api'
], function ($router) {
    Route::get('/', 'BranchController@index');
    Route::get('qrcode/{token}', 'BranchController@getByQrCode');
    Route::get('{key}', 'BranchController@getByKey');
    
    
    Route::post('/register', 'RegistrationController@store');
    
    Route::post('/call-waiter', 'CallWaiterController@store');
    Route::post('/call-waiter/{id}/resolve', 'CallWaiterController@resolve')->name('api.call-waiter.resolve');
});
