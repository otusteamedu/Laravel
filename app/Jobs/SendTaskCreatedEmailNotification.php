<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendTaskCreatedEmailNotification implements ShouldQueue
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
        try {
            // Отправляем email исполнителю задачи
            $this->sendEmailToExecutor();
            
            // Отправляем email создателю задачи (если это не один человек)
            if ($this->task->creator_id !== $this->task->executor_id) {
                $this->sendEmailToCreator();
            }

            // Email уведомления отправлены успешно

        } catch (\Exception $e) {
            Log::error('Ошибка отправки Email уведомления', [
                'task_id' => $this->task->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e; // Повторяем попытку через retry механизм
        }
    }

    private function sendEmailToExecutor(): void
    {
        $executor = $this->task->executor;
        
        Mail::send('emails.task-created-executor', [
            'task' => $this->task,
            'executor' => $executor,
            'creator' => $this->task->creator,
            'category' => $this->task->category,
            'priority' => $this->task->priority
        ], function ($message) use ($executor) {
            $message->to($executor->email, $executor->name)
                    ->subject('Вам назначена новая задача: ' . $this->task->title);
        });
    }

    private function sendEmailToCreator(): void
    {
        $creator = $this->task->creator;
        
        Mail::send('emails.task-created-creator', [
            'task' => $this->task,
            'creator' => $creator,
            'executor' => $this->task->executor,
            'category' => $this->task->category,
            'priority' => $this->task->priority
        ], function ($message) use ($creator) {
            $message->to($creator->email, $creator->name)
                    ->subject('Задача создана: ' . $this->task->title);
        });
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Не удалось отправить Email уведомление о создании задачи', [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);
    }
} 