<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Jobs\SendTaskCreatedEmailNotification;
use App\Jobs\SendTaskCreatedTelegramNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class TaskCreatedNotificationListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 1;

    public function handle(TaskCreated $event): void
    {
        $task = $event->task;

        try {
            // Запускаем Job для Telegram уведомления
            SendTaskCreatedTelegramNotification::dispatch($task);

            // Запускаем Job для Email уведомления
            SendTaskCreatedEmailNotification::dispatch($task);

        } catch (\Exception $e) {
            Log::error('Ошибка при запуске Jobs для уведомлений', [
                'task_id' => $task->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function failed(TaskCreated $event, \Throwable $exception): void
    {
        Log::error('Не удалось обработать событие создания задачи', [
            'task_id' => $event->task->id,
            'error' => $exception->getMessage()
        ]);
    }
}
