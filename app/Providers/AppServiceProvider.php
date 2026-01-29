<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Symfony\Component\HttpFoundation\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (session()->has('app_locale')) {
              if (!app()->runningInConsole() && session()->has('app_locale')) {
                 app()->setLocale(session('app_locale'));
              }
        }
        
        Route::macro('isWith', function (...$parameters) {
            foreach ($parameters as $parameter) {
                if (url()->current() == (!is_array($parameter)
                    ? route($parameter)
                    : route($parameter[0], $parameter[1] ?? []))
                ) {
                    return true;
                }
            }
            return false;
        });

        if (!app()->runningInConsole()) {
            $schoolSlug = $this->extractSchoolSlug();
            config()->set('subdomain.school', $schoolSlug);
        }
    }

    protected function extractSchoolSlug(): string
    {
        $host = request()->getHost();
        $root = parse_url(config('app.url'), PHP_URL_HOST);

        $subdomains = array_diff(explode('.', $host), explode('.', $root));

        return end($subdomains)
            ?: abort(Response::HTTP_UNAUTHORIZED);
    }
}
