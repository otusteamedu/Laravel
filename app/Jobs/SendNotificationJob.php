<?php

namespace App\Jobs;

use App\Services\NotifierInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Все что передано в конструктор будет сериализовано и записано в БД.
     * Когда запустится обработка очереди, эти данные будут вытащены из БД, десериализованы и переданы в метод handle()

     */
    public function __construct(
        private string $text,
        private int $recipientId
    )
    {
    }

    /**
     * Execute the job.
     * В метод handle() можно инжектить зависимости из контейнера
     */
    public function handle(NotifierInterface $notifier): void
    {
        $notifier->send($this->text, $this->recipientId);
    }
}
