<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySchoolSubdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $rootDomain = parse_url(config('app.url'), PHP_URL_HOST);

        $subdomains = array_diff(explode('.', $host), explode('.', $rootDomain));
        $schoolSlug = end($subdomains)
            ?: abort(Response::HTTP_UNAUTHORIZED);

        $systemMain = env('SYSTEM_MAIN', 'ecs');

        if ($schoolSlug === $systemMain) {
            session(['school_id' => null]);
            session(['school_name' => $systemMain]);

            return $next($request);
        }

        $school = School::where('sub_domain', $schoolSlug)->first()
            ?: abort(Response::HTTP_UNAUTHORIZED);

        session(['school_id' => $school->id]);
        session(['school_name' => $school->sub_domain]);

        return $next($request);
    }
}
