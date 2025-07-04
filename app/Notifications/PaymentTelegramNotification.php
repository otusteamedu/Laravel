<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class PaymentTelegramNotification extends Notification
{
    use Queueable;

    public function __construct(public string $notification)
    {}

    public function via($notifiable = null): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable = null)
    {
        $message = $this->notification;

        return TelegramMessage::create()
            ->to(config('services.telegram-bot-api.chat_id'))
            ->content($message);
    }
}