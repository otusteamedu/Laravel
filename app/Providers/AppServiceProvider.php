<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Infrastructure\PasswordHasher\PasswordHasherInterface;
use App\Infrastructure\PasswordHasher\LaravelPasswordHasher;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Используем Bootstrap для пагинации
        Paginator::useBootstrap();
    }
}
