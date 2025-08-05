<?php

namespace App\Domain\Task\Commands\DeleteTask;

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

        return $this->taskRepository->delete($command->id);
    }
}