<?php

namespace App\Providers;

use App\Repositories\LikeRepo\EloquentLikeRepo;
use App\Repositories\LikeRepo\LikeRepoInterface;
use App\Repositories\PostRepo\EloquentPostRepo;
use App\Repositories\PostRepo\PostRepoInterface;
use Illuminate\Support\ServiceProvider;

class ReposSeviceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PostRepoInterface::class, EloquentPostRepo::class);
        $this->app->bind(LikeRepoInterface::class, EloquentLikeRepo::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
