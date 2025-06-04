<?php

namespace App\Services\Commands\DeleteTask;

use App\Repositories\Tasks\TaskRepositoryInterface;
use App\Services\Exceptions\Tasks\TaskNotFoundException;

class Handler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function handle(Command $command): bool
    {
        $task = $this->taskRepository->find($command->id);

        if (!$task) {
            throw new TaskNotFoundException('Задача не найдена');
        }

        return $this->taskRepository->delete($task);
    }
} 