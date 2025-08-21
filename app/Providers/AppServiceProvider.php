<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repository\NewsRepositoryInterface;
use App\Application\UseCase\UpdateNewsUseCase;
use App\Application\UseCase\CreateNewsUseCase;
use App\Application\UseCase\DeleteNewsUseCase;
use App\Application\UseCase\ShowNewsUseCase;
use App\Application\UseCase\IndexNewsUseCase;
use App\Infrastructure\Repository\NewsRepository;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
        $this->app->bind(
            NewsRepositoryInterface::class,
            UpdateNewsUseCase::class
        );
        $this->app->bind(
            NewsRepositoryInterface::class,
            IndexNewsUseCase::class
        );
        
        $this->app->bind(
            NewsRepositoryInterface::class,
            ShowNewsUseCase::class
        );
        $this->app->bind(
            NewsRepositoryInterface::class,
            DeleteNewsUseCase::class
        );
        $this->app->bind(
            NewsRepositoryInterface::class,
            CreateNewsUseCase::class
        );
        $this->app->bind(
            NewsRepositoryInterface::class,
            NewsRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
       // Gate::define('news.update', [\App\Http\Controllers\News\Update::class, 'update']);
    }
}
