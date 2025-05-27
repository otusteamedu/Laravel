<?php

namespace App\Providers;

//use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Services\Category\Repositories\CategoryRepositoryInterface;
use App\Repositories\Category\CategoryRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Paginator::useBootstrap();
    }
}
