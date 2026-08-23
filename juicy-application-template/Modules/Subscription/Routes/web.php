<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'admin'], function(){
    Route::get('branches/{branch}/subscriptions', 'SubscriptionController@show')->name('admin.subscriptions.show');
    Route::post('branches/{branch}/subscriptions', 'SubscriptionController@store')->name('admin.subscriptions.store');
    Route::post('branches/{branch}/subscriptions/deactivate/{id}', 'SubscriptionController@deactivate')->name('admin.subscriptions.deactivate');
});
