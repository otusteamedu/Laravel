<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\OrderEmail;
use Illuminate\Support\Facades\Mail;

class SendOrderEmailNotification implements ShouldQueue
{
    public $tries = 3;

    public function __construct()
    {}

    public function handle(OrderConfirmed $event): void
    {
        $order = $event->order;
        $email = config('custom.adminEmail');
        Mail::to($email)->send(new OrderEmail($order));
    }
}
