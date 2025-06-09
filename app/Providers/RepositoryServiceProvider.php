<?php

namespace App\Providers;

use App\Repositories\PlayerRepository;
use App\Repositories\TeamRepository;
use App\Services\Team\TeamRepositoryInterface;
use App\Services\TeamPlayer\PlayerRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->singleton(PlayerRepositoryInterface::class, PlayerRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
