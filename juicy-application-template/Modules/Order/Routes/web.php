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



Route::prefix('admin')->group(function () {
    Route::resource('ordermethods', 'OrderMethodController');
    Route::get('ordermethods/activate/{id}', 'OrderMethodController@activate');
    Route::resource('paymentmethods', 'PaymentMethodController');
    Route::get('paymentmethods/activate/{id}', 'PaymentMethodController@activate');
    Route::resource('orderstatus', 'OrderStatusController');
    Route::get('orderstatus/activate/{id}', 'OrderStatusController@activate');
    Route::resource('orders', 'OrderController');
    Route::post('ajax_order_status', 'OrderController@ajax_order_status');
    // Route::post('ajax_order_time', 'OrderController@ajax_order_time');
    // Route::post('ajax_driver', 'OrderController@ajax_driver');
    Route::get('print-receipt/{id}', 'OrderController@printReceipt')->name('orders.print');
});
