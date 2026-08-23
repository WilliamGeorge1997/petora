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

// Route::prefix('common')->group(function() {
//     Route::get('/', 'CommonController@index');
// });
Route::get('/', function () {
    return redirect()->route('admin.login');
});
// Route::get('/', 'CommonController@home');
Route::prefix('admin')->group(function () {
    Route::get('/setting', 'CommonController@setting')->name('setting.index');
    Route::post('/setting', 'CommonController@savesetting');

    Route::post('/removeImage', 'CommonController@removeImage')->name('removeImage');

    Route::post('viewOrderNotify', 'CommonController@viewOrderNotify')->name('viewOrderNotify');

    Route::get('/logs', 'CommonController@logs')->name('logs.index');
    Route::resource('reviews', 'ReviewController')->only(['index', 'destroy']);
});
