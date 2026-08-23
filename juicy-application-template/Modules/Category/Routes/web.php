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

// Route::prefix('category')->group(function() {
//     Route::get('/', 'CategoryController@index');
// });

Route::prefix('admin')->group(function () {

    Route::get('categories/sort', 'CategoryController@sort')->name('categories.sort');
    Route::post('categories/sort', 'CategoryController@updateSortOrder')->name('categories.sort.update');

    Route::resource('categories', 'CategoryController');
    Route::get('categories/activate/{id}', 'CategoryController@activate');
});
