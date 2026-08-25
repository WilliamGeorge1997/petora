<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Admin\Models\Admin;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale');

        if (auth('admin')->check()) {
            /** @var Admin $admin */
            $admin = auth('admin')->user();
            $locale = $admin->locale ?? $locale;
        }

        if ($locale) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
