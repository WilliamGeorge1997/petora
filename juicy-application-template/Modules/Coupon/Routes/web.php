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

// Route::prefix('coupon')->group(function() {
//     Route::get('/', 'CouponController@index');
// });

Route::prefix('admin')->group(function() {
    Route::resource('coupons','CouponController');
    Route::get('coupons/activate/{id}','CouponController@activate');


    Route::resource('freeDelivery','FreeDeliveryController');
    Route::get('freeDelivery/activate/{id}','FreeDeliveryController@activate');

    
    Route::resource('discounts','DiscountController');
    Route::get('discounts/activate/{id}','DiscountController@activate');

});
