<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserServiceRepository;
use App\Services\userService\UserServiceRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //связь интерфейсов для сервисов с их реализацией в классах репозиториев
        $this->app->bind(UserServiceRepositoryInterface::class, UserServiceRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('isAdmin', ['App\Policy\MainAppUserGateSet', 'isAdmin']);
        Gate::define('editFio', ['App\Policy\MainAppUserGateSet', 'editFio']);
    }
}
