<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendTaskCreatedTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60]; // Задержки между попытками в секундах

    public function __construct(
        public readonly Task $task
    ) {
        // Ставим задачу в отдельную очередь для уведомлений
        $this->onQueue('notifications');
    }

    public function handle(): void
    {
        $botToken = config('telegram-logger.bot_token');
        $chatId = config('telegram-logger.chat_id');

        if (empty($botToken) || empty($chatId)) {
            Log::warning('Telegram уведомления отключены: не настроены bot_token или chat_id');
            return;
        }

        try {
            $message = $this->formatTaskMessage();

            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            if (!$response->successful()) {
                throw new \Exception("Telegram API error: " . $response->body());
            }

            // Уведомление отправлено успешно

        } catch (\Exception $e) {
            Log::error('Ошибка отправки Telegram уведомления', [
                'task_id' => $this->task->id,
                'error' => $e->getMessage()
            ]);

            throw $e; // Повторяем попытку через retry механизм
        }
    }

    private function formatTaskMessage(): string
    {
        $creator = $this->task->creator;
        $executor = $this->task->executor;
        $category = $this->task->category;
        $priority = $this->task->priority;

        $message = "<b>Создана новая задача</b>\n\n";
        $message .= "<b>Название:</b> {$this->task->title}\n";
        $message .= "<b>Описание:</b> " . (mb_strlen($this->task->description) > 100
            ? mb_substr($this->task->description, 0, 100) . '...'
            : $this->task->description) . "\n";
        $message .= "<b>Кем создана:</b> {$creator->name}\n";
        $message .= "<b>Исполнитель:</b> {$executor->name}\n";
        $message .= "<b>Категория:</b> {$category->name}\n";
        $message .= "<b>Приоритет:</b> {$priority->name}\n";
        $message .= "<b>Статус:</b> {$this->task->status}\n";

        if ($this->task->due_date) {
            $message .= "<b>Срок:</b> " . $this->task->due_date . "\n";
        }

        $message .= "\n<b>Создано:</b> " . $this->task->created_at->format('d.m.Y H:i');

        return $message;
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Не удалось отправить Telegram уведомление о создании задачи', [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);
    }
}
