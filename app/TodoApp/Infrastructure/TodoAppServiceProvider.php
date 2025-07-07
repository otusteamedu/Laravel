<?php

namespace App\TodoApp\Infrastructure;

use Illuminate\Support\ServiceProvider;
use App\Infrastructure\Telegram\TelegramService;
use App\Services\Repositories\Todo\TodoRepositoryInterface;
use App\TodoApp\Domain\Repositories\UserRepositoryInterface;
use App\Services\Repositories\UserSocialiteRepositoryInterface;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;
use App\TodoApp\Domain\Services\Telegram\TelegramServiceInterface;
use App\TodoApp\Infrastructure\Eloquent\Repositories\TodoRepository;
use App\TodoApp\Infrastructure\Eloquent\Repositories\UserRepository;
use App\TodoApp\Infrastructure\Eloquent\Repositories\ProjectRepository;
use App\TodoApp\Infrastructure\Eloquent\Repositories\UserSocialiteRepository;

class TodoAppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserSocialiteRepositoryInterface::class, UserSocialiteRepository::class);
        $this->app->bind(TodoRepositoryInterface::class, TodoRepository::class);
        $this->app->bind(TelegramServiceInterface::class, TelegramService::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Presentation/resources/views', 'todo-app');
        $this->mergeConfigFrom(__DIR__ . '/config/locale.php', 'locale');
        $this->loadRoutesFrom(__DIR__ . '/routes/todo-app.php');
    }
}
