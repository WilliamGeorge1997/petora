<?php

use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SetLocaleApi;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Added by EnvKit so shared (public) URLs keep the https scheme and
        // public host. Safe locally; remove to opt out.
        $middleware->trustProxies(at: '*');
        $middleware->redirectGuestsTo('/admin/login')
            ->redirectUsersTo('/admin/dashboard')
            ->web([SetLocale::class])
            ->api([SetLocaleApi::class])
            ->alias([
                'prevent-back-history' => PreventBackHistory::class,
                'permission' => PermissionMiddleware::class,
                'role' => RoleMiddleware::class,
                'role-or-permission' => RoleOrPermissionMiddleware::class,
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $isApiRequest = function (Request $request) {
            return $request->is('api/*') || $request->expectsJson();
        };

        $exceptions->shouldRenderJsonWhen(
            function (Request $request, Throwable $e) use ($isApiRequest) {
                return $isApiRequest($request);
            }
        )->render(function (ValidationException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) { 
                return fail(
                    status: false,
                    message: __('common::message.validation_error'),
                    errors: $e->errors(),
                    status_string: 'validation_error'
                );
            }
        })->render(function (AuthenticationException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                return fail(
                    status: false,
                    message: __('common::message.unauthenticated'),
                    errors: null,
                    status_string: 'unauthorized'
                );
            }
        });
    })
    ->create();
