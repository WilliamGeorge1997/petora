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

Route::prefix('admin')->group(function () {
    // Branch offer settings
    Route::get('branches/offer', 'BranchController@indexOffer')->name('branches.offer');
    Route::post('branches/offer', 'BranchController@updateOffer')->name('branches.offer.update');
    Route::delete('branches/offer', 'BranchController@destroyOffer')->name('branches.offer.destroy');

    // Branch settings page
    Route::get('branches/{id}/settings', 'BranchSettingController@edit')->name('branch.settings');
    Route::post('branches/{id}/settings', 'BranchSettingController@update')->name('branch.settings.update');

    // Branch QR page
    Route::get('branches/{id}/qr', 'BranchQrCodeController@edit')->name('branch.qr');
    Route::post('branches/{id}/qr', 'BranchQrCodeController@update')->name('branch.qr.update');

    // Branch Discounts page
    Route::get('branches/{id}/discounts', 'BranchOrderDiscountController@edit')->name('branch.discounts');
    Route::post('branches/{id}/discounts', 'BranchOrderDiscountController@update')->name('branch.discounts.update');

    //Delete Settings Images
    Route::post('branches/delete-setting-image', 'BranchController@deleteSettingImage')->name('branches.delete-setting-image');

    // Registrations
    Route::get('registrations', 'RegistrationController@index')->name('registrations.index');
    Route::get('registrations/{id}', 'RegistrationController@show')->name('registrations.show');
    Route::get('registrations/completed/{id}', 'RegistrationController@completed')->name('registrations.completed');
    Route::delete('registrations/{id}', 'RegistrationController@destroy')->name('registrations.destroy');

    //Branches
    Route::resource('branches', 'BranchController')->except('show');
    // Route::get('branches/{id}/qrcode', 'BranchController@generateQrCode')->name('branches.qrcode');
    Route::get('branches/activate/{id}', 'BranchController@activate');
    Route::post('branches/{id}/update-theme', 'BranchController@updateTheme')->name('branches.update-theme');

    Route::post('branches/delete-setting-image', 'BranchSettingController@deleteSettingImage')->name('branches.delete-setting-image');
});

Route::get('{code}', 'ShortUrlController');
