<?php

namespace App\Services\Commands\CreateTask;

use App\Models\Task;
use App\Repositories\Tasks\TaskRepositoryInterface;

class Handler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function handle(Command $command): bool
    {
        $task = new Task();

        $task->title = $command->title;
        $task->description = $command->description;
        $task->executor_id = $command->executorId;
        $task->category_id = $command->categoryId;
        $task->priority_id = $command->priorityId;
        $task->creator_id = $command->creatorId;
        $task->status = $command->status;

        if ($command->dueDate) {
            $task->due_date = $command->dueDate;
        }

        return $this->taskRepository->save($task);
    }
} 