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

// Route::prefix('product')->group(function() {
//     Route::get('/', 'ProductController@index');
// });


Route::prefix('admin')->group(function () {

    // Export Products with Attributes
    Route::get('products/export-with-attributes', 'ProductController@exportWithAttributes')->name('products.export-with-attributes');
    Route::resource('products', 'ProductController')->except('show');
    Route::get('sliders', 'ProductController@sliders')->name('sliders.index');
    Route::get('products/activate/{id}', 'ProductController@activate');


    Route::get('product/{id}/attributes', 'ProductController@productAttributes');
    Route::get('product/{id}/add_attribute', 'ProductController@attributeCreate');
    Route::post('product/{id}/add_attribute', 'ProductController@attributeStore');
    Route::get('attribute/{id}/edit', 'ProductController@editProductAttribute');
    Route::put('attribute/{id}/update', 'ProductController@updateProductAttribute');
    Route::delete('attribute/{id}/delete', 'ProductController@deleteProductAttribute');
    Route::post('deleteProductPhoto', 'ProductController@deleteProductPhoto');

    //Addons
    Route::get('addons/sort', 'AddonController@sort')->name('addons.sort');
    Route::post('addons/sort', 'AddonController@reorder')->name('addons.sort.update');
    Route::resource('addons', 'AddonController');
    Route::get('addons/activate/{id}', 'AddonController@activate');

    //Sides
    Route::get('sides/sort', 'SideController@sort')->name('sides.sort');
    Route::post('sides/sort', 'SideController@reorder')->name('sides.sort.update');
    Route::resource('sides', 'SideController');
    Route::get('sides/activate/{id}', 'SideController@activate');
});
