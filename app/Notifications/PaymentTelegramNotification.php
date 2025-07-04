<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class PaymentTelegramNotification extends Notification
{
    use Queueable;

    public function __construct(public string $uid, public string $event, public int $amount)
    {}

    public function via($notifiable = null): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable = null)
    {
        $message = 'Поступила новая оплата' . PHP_EOL;
        $message .= 'Платеж: ' . $this->uid . PHP_EOL;
        $message .= 'Статус: ' . $this->event . PHP_EOL;
        $message .= 'Сумма: ' . $this->amount . ' руб.';

        return TelegramMessage::create()
            ->to(config('services.telegram-bot-api.chat_id'))
            ->content($message);
    }
}