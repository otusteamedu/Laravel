<?php

namespace App\Providers;

use App\Services\PostLikeService\PostLikeServiceInterface;
use App\Services\PostLikeService\PostLikeService;
use Illuminate\Support\ServiceProvider;

class PostLikeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PostLikeServiceInterface::class, PostLikeService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
