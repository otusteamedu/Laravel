<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Services\OrdersService;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\OrderEmail;
use Illuminate\Support\Facades\Mail;

class SendOrderEmailNotification implements ShouldQueue
{
    public $tries = 3;

    public function __construct(private OrdersService $service)
    {}

    public function handle(OrderConfirmed $event): void
    {
        $orderId = $event->orderId;
        $email = config('custom.adminEmail');
        $order = $this->service->getById($orderId);
        Mail::to($email)->send(new OrderEmail($order));
    }
}
