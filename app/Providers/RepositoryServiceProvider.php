<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Eloquent\CategoryRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Product::created(function ($product) {
            $this->warmCache();
        });

        Product::updated(function ($product) {
            $this->warmCache();
        });

        Product::deleted(function ($product) {
            $this->warmCache();
        });
    }

    protected function warmCache(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        dispatch(function () {
            \Artisan::call('cache:warm-products');
        })->afterResponse();
    }
}
