<?php

namespace App\Providers;

use App\Repositories\Categories\CategoryRepository;
use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Repositories\Tasks\TaskRepository;
use App\Repositories\Tasks\TaskRepositoryInterface;
use App\Repositories\Users\UserRepository;
use App\Repositories\Users\UserRepositoryInterface;
use App\Domain\Task\Repositories\TaskRepositoryInterface as DomainTaskRepositoryInterface;
use App\Infrastructure\Task\Repositories\EloquentTaskRepository;
use App\Domain\Task\Commands\CreateTask\Handler as CreateTaskHandler;
use App\Domain\Task\Commands\UpdateTask\Handler as UpdateTaskHandler;
use App\Domain\Task\Commands\ChangeTaskStatus\Handler as ChangeTaskStatusHandler;
use App\Domain\Task\Commands\DeleteTask\Handler as DeleteTaskHandler;
use App\Services\Tasks\TaskDomainService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Старые репозитории для обратной совместимости
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        
        // Новый DDD репозиторий для Task агрегата
        $this->app->bind(DomainTaskRepositoryInterface::class, EloquentTaskRepository::class);
        
        // Регистрируем хэндлеры команд
        $this->app->bind(CreateTaskHandler::class);
        $this->app->bind(UpdateTaskHandler::class);
        $this->app->bind(ChangeTaskStatusHandler::class);
        $this->app->bind(DeleteTaskHandler::class);
        
        // Регистрируем доменный сервис
        $this->app->bind(TaskDomainService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 