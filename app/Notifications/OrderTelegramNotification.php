<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderTelegramNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {}

    public function via($notifiable = null): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable = null)
    {
        $message = 'Новый заказ в Смартлайн' . PHP_EOL;
        $message .= 'Сделан заказ № ' . $this->order->getId() . PHP_EOL;
        $message .= 'Клиент: ' . $this->order->getUser()->name . ' (' . $this->order->getUser()->email . ')' . PHP_EOL;
        $message .= 'Сумма: ' . $this->order->getTotal() . ' руб.' . PHP_EOL;

        return TelegramMessage::create()
            ->to(config('services.telegram-bot-api.chat_id'))
            ->content($message);
    }
}