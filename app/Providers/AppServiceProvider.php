<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
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
        if (env('USE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        Carbon::setLocale(config('app.locale'));

        View::share('appLocale', str_replace('_', '-', config('app.locale')));
    }
}
