<?php

namespace App\Listeners\Todo;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Todo\UserAssignTodoRoleEvent;
use App\Services\UseCases\Commands\Mail\Todo\UserAdd\Command;
use App\Services\UseCases\Commands\Mail\Todo\UserAdd\Handler;

class UserAssignTodoRoleMailNotofy implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private Handler $handler,
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserAssignTodoRoleEvent $event): void
    {
        try {
            $this->handler->handle(new Command(
                userId: $event->userId,
                projectId: $event->projectId,
                todoId: $event->todoId,
                role: $event->role
            ));
        } catch (Exception $exception) {
            Log::error("Ошибка отправки email о назначении на роль {$event->role} в задаче {$event->todoId} пользователю {$event->userId}. {$exception->getMessage()}");
        }
    }
}
