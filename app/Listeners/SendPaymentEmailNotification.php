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
        $arr = json_decode($notification, true);
        $uid = $arr['object']['id'] ?? '';
        $event = $arr['event'] ?? '';
        $amount = (int) ($arr['object']['amount']['value'] ?? 0);

        $email = config('custom.adminEmail');
        Mail::to($email)->send(new PaymentEmail($uid, $event, $amount));
    }
}
