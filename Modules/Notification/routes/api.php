<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\Api\NotificationController;

Route::prefix('client')->group(function () {
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/allow', [NotificationController::class, 'allowNotification']);
    Route::post('notifications/read', [NotificationController::class, 'readNotification']);
    Route::get('notifications/unread-count', [NotificationController::class, 'unReadNotificationsCount']);
});

