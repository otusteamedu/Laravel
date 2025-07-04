<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\PaymentEmail;
use Illuminate\Support\Facades\Mail;

class SendPaymentEmailNotification implements ShouldQueue
{
    public $tries = 3;

    public function __construct()
    {}

    public function handle(PaymentConfirmed $event): void
    {
        $notification = $event->notification;
        $email = config('custom.adminEmail');
        Mail::to($email)->send(new PaymentEmail($notification));
    }
}
