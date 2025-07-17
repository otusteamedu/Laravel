<?php

namespace App\Providers;

use App\Repositories\Area\AreaRepository;
use App\Repositories\Area\AreaRepositoryInterface;
use App\Repositories\Measure\MeasureRepository;
use App\Repositories\Measure\MeasureRepositoryInterface;
use App\Services\Area\AreaService;
use App\Services\Area\AreaServiceInterface;
use App\Services\Measure\MeasureService;
use App\Services\Measure\MeasureServiceInterface;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        $this->app->bind(
            AreaRepositoryInterface::class,
            AreaRepository::class
        );
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
    }
}
