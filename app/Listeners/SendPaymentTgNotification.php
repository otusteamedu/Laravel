<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\PaymentTelegramNotification;
use Illuminate\Support\Facades\Notification;

class SendPaymentTgNotification implements ShouldQueue
{
    public $tries = 3;
    
    public function __construct()
    {}

    public function handle(PaymentConfirmed $event): void
    {
        $notification = /*$event->notification;*/'Получено уведомление о платеже';
        Notification::send(null, new PaymentTelegramNotification($notification));
    }
}
