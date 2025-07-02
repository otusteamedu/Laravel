<?php

namespace App\Providers;

use App\Infrastructure\Cache\CacheInterface;
use App\Infrastructure\Cache\LaravelCache;
use App\Infrastructure\Eloquent\Repositories\Categories\CategoryRepository;
use App\Infrastructure\Eloquent\Repositories\News\NewsRepository;
use App\Infrastructure\Eloquent\Repositories\Users\UserRepository;
use App\Infrastructure\PasswordHasher\LaravelPasswordHasher;
use App\Infrastructure\PasswordHasher\PasswordHasherInterface;
use App\Policies\CategoryPolicy;
use App\Policies\NewsPolicy;
use App\Services\Repositories\CategoryRepositoryInterface;
use App\Services\Repositories\NewsRepositoryInterface;
use App\Services\Repositories\UserRepositoryInterface;
use App\Services\Telegram\TelegramService;
use App\Services\Telegram\TelegramServiceInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            NewsRepositoryInterface::class,
            NewsRepository::class
        );

        $this->app->bind(
            PasswordHasherInterface::class,
            LaravelPasswordHasher::class
        );

        $this->app->bind(
            CacheInterface::class,
            LaravelCache::class
        );

        $this->app->bind(
            TelegramServiceInterface::class,
            TelegramService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Paginator::useBootstrap();

        Gate::define('category.create', [CategoryPolicy::class, 'create']);
        Gate::define('category.update', [CategoryPolicy::class, 'update']);
        Gate::define('category.delete', [CategoryPolicy::class, 'delete']);

        Gate::define('news.update', [NewsPolicy::class, 'update']);
        Gate::define('news.delete', [NewsPolicy::class, 'delete']);
    }
}
