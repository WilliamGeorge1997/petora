<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Modules\Branch\Entities\BranchSetting;

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



Route::get('/privacy', function () {
    return view('privacy');
});

// Route::fallback(function () {
//     return redirect('/admin/dashboard');
// });
