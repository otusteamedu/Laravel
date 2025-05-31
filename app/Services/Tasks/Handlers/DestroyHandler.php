<?php
namespace App\Services\Tasks\Handlers;

use App\Services\Tasks\Exceptions\TaskNotFoundException;
use App\Repositories\Tasks\TaskRepositoryInterface;

class DestroyHandler{

    public function __construct(private TaskRepositoryInterface $taskRepository)
    {
    }

    public function __invoke(int $id): ?bool
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            throw new TaskNotFoundException('Задача не найдена');
        }

        return $this->taskRepository->delete($task);
    }
} 