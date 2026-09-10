<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminAuthController;
use Modules\Admin\Http\Controllers\AdminController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AdminAuthController::class, 'EditProfile'])->name('edit.profile');
    Route::post('/profile', [AdminAuthController::class, 'updateProfile'])->name('update.profile');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('admins', AdminController::class);
    Route::get('admins/activate/{id}', [AdminController::class, 'activate'])->name('activate');

    // Route::resource('roles', RoleController::class);
});
