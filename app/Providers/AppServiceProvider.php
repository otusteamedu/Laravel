<?php

namespace App\Providers;

use App\Http\Middleware\SetLocaleMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Server\MiddlewareInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(TeamGatesProvider::class);
        $this->app->bind(
            MiddlewareInterface::class,
            SetLocaleMiddleware::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
