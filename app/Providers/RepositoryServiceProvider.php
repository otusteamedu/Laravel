<?php

namespace App\Providers;

use App\Repositories\PlayerRepository;
use App\Repositories\TeamRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserRoleRepository;
use App\Services\Team\TeamRepositoryInterface;
use App\Services\TeamPlayer\PlayerRepositoryInterface;
use App\Services\User\UserRepositoryInterface;
use App\Services\UserRole\UserRoleRepositoryInterface;
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
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(UserRoleRepositoryInterface::class, UserRoleRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
