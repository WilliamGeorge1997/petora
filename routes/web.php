<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('admin.dashboard');
});

Route::fallback(function () {
    return response()->view('common::errors.404', [], 404);
});
