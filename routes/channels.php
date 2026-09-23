<?php

use Illuminate\Support\Facades\Broadcast;
use Modules\Admin\Models\Admin;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Admin Live Orders Console
Broadcast::channel('orders.live', function ($user) {
    return $user instanceof Admin;
}, ['guards' => ['admin']]);

// Client Mobile App
Broadcast::channel('client.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}, ['guards' => ['client']]);

// Driver Mobile App
Broadcast::channel('driver.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}, ['guards' => ['driver']]);
