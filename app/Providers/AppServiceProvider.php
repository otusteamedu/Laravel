<?php

namespace App\Providers;

use App\CacheDecorator\Area\AreaRepositoryCacheDecorator;
use App\EloquentModels\User;
use App\Policies\Fibonachi\FibonachiPolicy;
use App\Repositories\Area\AreaRepository;
use App\Services\Area\AreaRepositoryInterface;
use App\Repositories\Measure\MeasureRepository;
use App\Repositories\Measure\MeasureRepositoryInterface;
use App\Services\Area\AreaService;
use App\Services\Area\AreaServiceInterface;
use App\Services\Measure\MeasureService;
use App\Services\Measure\MeasureServiceInterface;
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
            AreaServiceInterface::class,
            AreaService::class
        );
        $this->app->singleton(AreaRepositoryInterface::class, function ($app) {
            $repository = new AreaRepository();
            return new AreaRepositoryCacheDecorator($repository);
        });
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
            'recipe' => \App\EloquentModels\Recipe::class,
            'product' => \App\EloquentModels\Product::class,
        ]);

        Gate::policy(User::class, FibonachiPolicy::class);
    }
}
