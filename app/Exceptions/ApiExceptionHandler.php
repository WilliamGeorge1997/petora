<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler
{
    protected static array $map = [
        ValidationException::class => [
            'message' => 'common::message.validation_error',
            'status_string' => 'validation_error',
        ],
        AuthenticationException::class => [
            'message' => 'common::message.unauthenticated',
            'status_string' => 'unauthorized',
        ],
        AuthorizationException::class => [
            'message' => 'common::message.forbidden',
            'status_string' => 'forbidden',
        ],
        AccessDeniedHttpException::class => [
            'message' => 'common::message.forbidden',
            'status_string' => 'forbidden',
        ],
        NotFoundHttpException::class => [
            'message' => 'common::message.not_found',
            'status_string' => 'not_found',
        ],
        MethodNotAllowedHttpException::class => [
            'message' => 'common::message.method_not_allowed',
            'status_string' => 'method_not_allowed',
        ],
    ];

    public static function render(Throwable $e, Request $request): ?JsonResponse
    {
        $isApiCall = $request->is('api/*') || $request->expectsJson();

        if (! $isApiCall) {
            return null;
        }

        foreach (static::$map as $exceptionClass => $config) {
            if ($e instanceof $exceptionClass) {
                return fail(
                    status: false,
                    message: __($config['message']),
                    errors: $e instanceof ValidationException ? $e->errors() : null,
                    status_string: $config['status_string']
                );
            }
        }

        return null;
    }
}
