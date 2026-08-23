<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('admin')->group(function() {
    Route::get('galleries/sort', 'GalleryController@sort')->name('galleries.sort');
    Route::post('galleries/sort', 'GalleryController@reorder')->name('galleries.sort.update');
    Route::resource('galleries', 'GalleryController')->except(['show']);
    Route::get('galleries/{id}/activate', 'GalleryController@activate')->name('galleries.activate');
});
