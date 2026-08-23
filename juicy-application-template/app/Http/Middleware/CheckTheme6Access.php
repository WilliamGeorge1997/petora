<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTheme6Access
{
    public function handle(Request $request, Closure $next)
    {
        $admin = auth('admin')->user();

        if ($admin->hasRole('Branch Manager')) {
            $theme = optional(optional($admin->branch)->settings)->theme;
            if ($theme != 6) {
                return abort(403, 'Unauthorized action.');
            }
        }

        return $next($request);
    }
}
