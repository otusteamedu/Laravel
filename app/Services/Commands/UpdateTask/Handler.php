<?php

namespace App\Services\Commands\UpdateTask;

use App\Repositories\Tasks\TaskRepositoryInterface;
use App\Services\Exceptions\Tasks\TaskNotFoundException;
use App\Services\DTO\Tasks\TaskDTO;

class Handler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function handle(Command $command): TaskDTO
    {
        $task = $this->taskRepository->find($command->id);

        if (!$task) {
            throw new TaskNotFoundException('Задача не найдена');
        }

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

        $this->taskRepository->save($task);

        return new TaskDTO(
            id: $task->id,
            title: $task->title,
            description: $task->description,
            executorId: $task->executor_id,
            executorName: $task->executor ? $task->executor->name : '',
            categoryId: $task->category_id,
            categoryName: $task->category ? $task->category->name : '',
            categoryColor: $task->category ? $task->category->color : '',
            priorityId: $task->priority_id,
            priorityName: $task->priority ? $task->priority->name : '',
            creatorId: $task->creator_id,
            creatorName: $task->creator ? $task->creator->name : '',
            status: $task->status ?? 'новая',
            dueDate: $task->due_date,
            createdAt: $task->created_at,
            updatedAt: $task->updated_at,
        );
    }
} 