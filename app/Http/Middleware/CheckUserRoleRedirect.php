<?php

namespace App\Http\Middleware;

use App\Acl\Acl;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRoleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (!$user) {
            return to_route('auth.login.show-form');
        }

        if ($user->hasAnyRole([Acl::ROLE_SUPER_ADMIN, Acl::ROLE_ADMIN]) && ! Route::is(['admin.*'])) {
            return to_route('admin.dashboard');
        }

        if ($user->hasAnyRole([Acl::ROLE_STAFF, Acl::ROLE_TEACHER]) && ! Route::is(['staff.*'])) {
            return to_route('staff.dashboard');
        }

        return $next($request);
    }
}
