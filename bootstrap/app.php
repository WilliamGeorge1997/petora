<?php

use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo('/admin/login')
            ->redirectUsersTo('/admin/dashboard')
            ->web([SetLocale::class])
            ->alias([
                'prevent-back-history' => PreventBackHistory::class,
                'permission' => PermissionMiddleware::class,
                'role' => RoleMiddleware::class,
                'role-or-permission' => RoleOrPermissionMiddleware::class,
                // 'set-locale' => SetLocale::class,
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
