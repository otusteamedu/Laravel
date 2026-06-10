<?php
namespace Src\Orders\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\OrdersEventSource\Domain\Contracts\EventStore;
use Src\OrdersEventSource\Domain\Events\OrderPaid;
use Src\OrdersEventSource\Infrastructure\EventListeners\SendOrderPaidEmail;
use Src\OrdersEventSource\Infrastructure\EventListeners\SendOrderPaidToKafka;
use Src\OrdersEventSource\Infrastructure\Stores\EloquentEventStore;

class OrderServiceProvider extends ServiceProvider
{
    protected $listener = [
        OrderPaid::class => [
            SendOrderPaidEmail::class,
            SendOrderPaidToKafka::class,
        ]
    ];
    public function register(): void
    {
        $this->app->bind(
            EventStore::class,
            EloquentEventStore::class
        );
    }
}