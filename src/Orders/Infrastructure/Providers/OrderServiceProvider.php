<?php
namespace Src\Orders\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Orders\Domain\Repositories\OrderRepository;
use Src\Orders\Infrastructure\Persistence\EloquentOrderRepository;

class OrderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            OrderRepository::class,
            EloquentOrderRepository::class
        );
    }
}