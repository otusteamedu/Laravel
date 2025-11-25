<?php

namespace App\Providers;

use App\Services\PostService\PostServiceInterface;
use App\Services\PostService\PostService;
use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            PostServiceInterface::class,
            PostService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
