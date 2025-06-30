<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\OrderTelegramNotification;
use Illuminate\Support\Facades\Notification;

class SendOrderTgNotification implements ShouldQueue
{
    public $tries = 3;
    
    public function __construct()
    {}

    public function handle(OrderConfirmed $event): void
    {
        $order = $event->order;
        Notification::send(null, new OrderTelegramNotification($order));
    }
}
