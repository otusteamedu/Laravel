<?php

namespace App\Providers;

//use Illuminate\Pagination\Paginator;
use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use App\Services\Category\Repositories\CategoryRepositoryInterface;
use App\Services\News\Repositories\NewsRepositoryInterface;
use App\Services\User\Repositories\UserRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\News\NewsRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Gate;
use App\Policies\NewsPolicy;
use App\Policies\CategoryPolicy;

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
