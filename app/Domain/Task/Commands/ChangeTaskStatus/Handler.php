<?php

namespace App\Domain\Task\Commands\ChangeTaskStatus;

use App\Domain\Task\Repositories\TaskRepositoryInterface;
use App\Domain\Task\ValueObjects\TaskStatus;
use App\Services\Exceptions\Tasks\TaskNotFoundException;

class Handler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function handle(Command $command): bool
    {
        $task = $this->taskRepository->findById($command->taskId);

        if (!$task) {
            throw new TaskNotFoundException('Задача не найдена');
        }

        // Используем бизнес-методы для изменения статуса
        match ($command->status->value()) {
            TaskStatus::IN_PROGRESS => $task->startWork(),
            TaskStatus::COMPLETED => $task->complete(),
            TaskStatus::CANCELLED => $task->cancel(),
            default => throw new \InvalidArgumentException('Недопустимый переход статуса')
        };

        return $this->taskRepository->save($task);
    }
}