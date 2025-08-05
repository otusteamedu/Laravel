<?php

namespace App\Domain\Task\Commands\UpdateTask;

use App\Domain\Task\Repositories\TaskRepositoryInterface;
use App\Services\Exceptions\Tasks\TaskNotFoundException;

class Handler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function handle(Command $command): bool
    {
        $task = $this->taskRepository->findById($command->id);

        if (!$task) {
            throw new TaskNotFoundException('Задача не найдена');
        }

        // Обновляем детали задачи
        $task->updateDetails(
            title: $command->title,
            description: $command->description,
            dueDate: $command->dueDate
        );

        // Обновляем назначение и категорию
        $task->assignToExecutor($command->executorId);
        $task->changeCategory($command->categoryId);
        $task->changePriority($command->priorityId);

        return $this->taskRepository->save($task);
    }
}