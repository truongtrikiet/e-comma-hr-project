<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocaleMiddleware
{
    /**
     * Handle an incoming request and set app locale from session.
     */
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('app_locale')) {
            app()->setLocale(session('app_locale'));
        }

        return $next($request);
    }
}
