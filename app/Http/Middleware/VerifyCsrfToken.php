<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            if (app()->environment('production')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message'  => 'Phiên làm việc đã hết hạn. Vui lòng đăng nhập lại.',
                        'redirect' => route('auth.login.show-form'),
                    ], 419);
                }
                return redirect()
                    ->route('auth.login.show-form')
                    ->with('error', 'Phiên làm việc đã hết hạn. Vui lòng đăng nhập lại.');
            }

            throw $e;
        }
    }
}
