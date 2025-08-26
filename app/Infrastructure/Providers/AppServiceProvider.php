<?php

namespace App\Infrastructure\Providers;

use App\Interfaces\CacheDecorator\Area\CachedAreaService;
use App\Infrastructure\EloquentModels\User;
use App\Domain\Policies\Fibonachi\FibonachiPolicy;
use App\Infrastructure\Repositories\Area\AreaRepository;
use App\Application\Services\Area\AreaRepositoryInterface;
use App\Infrastructure\Repositories\Measure\MeasureRepository;
use App\Application\Services\Area\AreaService;
use App\Application\Services\Area\AreaServiceInterface;
use App\Application\Services\Category\CategoryRepositoryInterface;
use App\Application\Services\Category\CategoryService;
use App\Application\Services\Category\CategoryServiceInterface;
use App\Application\Services\Measure\MeasureRepositoryInterface;
use App\Application\Services\Measure\MeasureService;
use App\Application\Services\Measure\MeasureServiceInterface;
use App\Application\Services\MeasureProductRecipe\MeasureProductRecipeRepositoryInterface;
use App\Application\Services\MeasureProductRecipe\MeasureProductRecipeService;
use App\Application\Services\MeasureProductRecipe\MeasureProductRecipeServiceInterface;
use App\Application\Services\Product\ProductRepositoryInterface;
use App\Application\Services\Product\ProductService;
use App\Application\Services\Product\ProductServiceInterface;
use App\Application\Services\Recipe\RecipeRepositoryInterface;
use App\Application\Services\Recipe\RecipeService;
use App\Application\Services\Recipe\RecipeServiceInterface;
use App\Infrastructure\Repositories\Category\CategoryRepository;
use App\Infrastructure\Repositories\MeasureProductRecipe\MeasureProductRecipeRepository;
use App\Infrastructure\Repositories\Product\ProductRepository;
use App\Infrastructure\Repositories\Recipe\RecipeRepository;
use App\Interfaces\Console\Kernel;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
            CategoryServiceInterface::class,
            CategoryService::class
        );
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            RecipeServiceInterface::class,
            RecipeService::class
        );
        $this->app->bind(
            RecipeRepositoryInterface::class,
            RecipeRepository::class
        );

        $this->app->bind(
            ProductServiceInterface::class,
            ProductService::class
        );
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        $this->app->bind(
            MeasureServiceInterface::class,
            MeasureService::class
        );
        $this->app->bind(
            MeasureRepositoryInterface::class,
            MeasureRepository::class
        );

        $this->app->bind(
            MeasureProductRecipeServiceInterface::class,
            MeasureProductRecipeService::class
        );
        $this->app->bind(
            MeasureProductRecipeRepositoryInterface::class,
            MeasureProductRecipeRepository::class
        );

        $this->app->singleton(
            ConsoleKernel::class,
            Kernel::class
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

        Passport::ignoreRoutes();
        Passport::enablePasswordGrant();
    }
}
