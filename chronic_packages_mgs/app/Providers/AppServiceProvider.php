<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrapFive();

        $appUrl = (string) config('app.url');
        $forceHttps = filter_var(env('APP_FORCE_HTTPS', false), FILTER_VALIDATE_BOOL)
            || str_starts_with($appUrl, 'https://');

        if ($forceHttps) {
            URL::forceScheme('https');

            if ($appUrl !== '') {
                URL::forceRootUrl(rtrim($appUrl, '/'));
            }
        }
    }
}
