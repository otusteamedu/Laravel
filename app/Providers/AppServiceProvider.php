<?php

namespace App\Providers;

use App\Application\Services\CartAppService;
use App\Application\Services\CategoryAppService;
use App\Application\Services\OrderAppService;
use App\Application\Services\ProductAppService;
use App\Domain\Cart\Repositories\CartRepositoryInterface;
use App\Domain\Cart\Services\CartService;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Domain\Category\Services\CategoryService;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Domain\Order\Services\OrderService;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Product\Services\ProductService;
use App\Infrastructure\Eloquent\Repositories\CartRepository;
use App\Infrastructure\Eloquent\Repositories\CategoryRepository;
use App\Infrastructure\Eloquent\Repositories\OrderRepository;
use App\Infrastructure\Eloquent\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerRepositories();
        $this->registerDomainServices();
        $this->registerApplicationServices();

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\\Factories\\' . class_basename($modelName) . 'Factory';
        });
    }

    /**
     * Register repository bindings
     */
    private function registerRepositories(): void
    {
        // Product repositories
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Register domain services
     */
    private function registerDomainServices(): void
    {
        // Product services
        $this->app->singleton(ProductService::class, function ($app) {
            return new ProductService(
                $app->make(ProductRepositoryInterface::class)
            );
        });

        // Category services
        $this->app->singleton(CategoryService::class, function ($app) {
            return new CategoryService(
                $app->make(CategoryRepositoryInterface::class)
            );
        });

        // Cart services
        $this->app->singleton(CartService::class, function ($app) {
            return new CartService(
                $app->make(CartRepositoryInterface::class)
            );
        });

        // Order services
        $this->app->singleton(OrderService::class, function ($app) {
            return new OrderService(
                $app->make(OrderRepositoryInterface::class),
                $app->make(CartService::class)
            );
        });
    }


    /**
     * Register application services
     */
    private function registerApplicationServices(): void
    {
        // Product application services
        $this->app->singleton(ProductAppService::class, function ($app) {
            return new ProductAppService(
                $app->make(ProductService::class)
            );
        });


        // Category application services
        $this->app->singleton(CategoryAppService::class, function ($app) {
            return new CategoryAppService(
                $app->make(CategoryService::class)
            );
        });


        // Cart application services
        $this->app->singleton(CartAppService::class, function ($app) {
            return new CartAppService(
                $app->make(CartService::class),
                $app->make(ProductRepositoryInterface::class)
            );
        });

        // Order application services
        $this->app->singleton(OrderAppService::class, function ($app) {
            return new OrderAppService(
                $app->make(OrderService::class),
                $app->make(CartService::class)
            );
        });
    }
}
