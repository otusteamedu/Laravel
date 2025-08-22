<?php

namespace App\Infrastructure\Providers;

use App\Interfaces\CacheDecorator\Area\CachedAreaService;
use App\Infrastructure\EloquentModels\User;
use App\Domain\Policies\Fibonachi\FibonachiPolicy;
use App\Infrastructure\Repositories\Area\AreaRepository;
use App\Application\Services\Area\AreaRepositoryInterface;
use App\Infrastructure\Repositories\Measure\MeasureRepository;
use App\Infrastructure\Repositories\Measure\MeasureRepositoryInterface;
use App\Application\Services\Area\AreaService;
use App\Application\Services\Area\AreaServiceInterface;
use App\Application\Services\Measure\MeasureService;
use App\Application\Services\Measure\MeasureServiceInterface;
use Illuminate\Database\Eloquent\Relations\Relation;
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
            AreaRepositoryInterface::class,
            AreaRepository::class
        );
        $this->app->bind(AreaService::class);
        $this->app->when(CachedAreaService::class)
          ->needs(AreaServiceInterface::class)
          ->give(AreaService::class);
        $this->app->bind(AreaServiceInterface::class, CachedAreaService::class);
        $this->app->bind(
            MeasureServiceInterface::class,
            MeasureService::class
        );
        $this->app->bind(
            MeasureRepositoryInterface::class,
            MeasureRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'recipe' => \App\Infrastructure\EloquentModels\Recipe::class,
            'product' => \App\Infrastructure\EloquentModels\Product::class,
        ]);

        Gate::policy(User::class, FibonachiPolicy::class);
    }
}
