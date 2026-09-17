<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\NotificationController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('notifications/city-clients/{city_id?}', [NotificationController::class, 'getCityClients'])->name('notifications.city-clients');
    Route::get('notifications/{notification}/read', [NotificationController::class, 'readNotification'])->name('notifications.read');
    Route::resource('notifications', NotificationController::class)->names('notifications')->only(['index', 'create', 'store', 'destroy']);
});


