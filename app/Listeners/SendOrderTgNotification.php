<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Services\OrdersService;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\OrderTelegramNotification;
use Illuminate\Support\Facades\Notification;

class SendOrderTgNotification implements ShouldQueue
{
    public $tries = 3;
    
    public function __construct(private OrdersService $service)
    {}

    public function handle(OrderConfirmed $event): void
    {
        $orderId = $event->orderId;
        $order = $this->service->getById($orderId);
        Notification::send(null, new OrderTelegramNotification($order));
    }
}
