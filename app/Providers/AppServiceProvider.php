<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Infrastructure\PasswordHasher\PasswordHasherInterface;
use App\Infrastructure\PasswordHasher\LaravelPasswordHasher;
use App\Services\Cache\CacheServiceInterface;
use App\Services\Cache\CacheService;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Регистрируем сервис-провайдер для репозиториев
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->bind(PasswordHasherInterface::class, LaravelPasswordHasher::class);
        $this->app->bind(CacheServiceInterface::class, CacheService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Используем Bootstrap для пагинации
        Paginator::useBootstrap();

        // Настраиваем редирект для авторизованных пользователей
        RedirectIfAuthenticated::redirectUsing(function () {
            return route('tasks.index');
        });

        // Определяем Gate для админского доступа
        Gate::define('admin-access', function ($user) {
            return $user->isAdmin();
        });
    }
}
