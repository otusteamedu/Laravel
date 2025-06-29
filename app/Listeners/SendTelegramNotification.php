<?php

namespace App\Listeners;

use App\Events\NewsPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Telegram\TelegramService;
use Illuminate\Support\Facades\Log;

class SendTelegramNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct(private TelegramService $telegramService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewsPublished $event): void
    {
        $message = "Новая новость опубликована: " . $event->title;

        $sent = $this->telegramService->sendMessage($message);

        if (!$sent) {
            // Логируем ошибку или предпринимаем повторную попытку
            Log::error("Не удалось отправить Telegram сообщение для новости ID {$event->id}");
        }
    }
}
