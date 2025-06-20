<?php

namespace App\Services\Queries\FetchTaskById;

use App\Repositories\Tasks\TaskRepositoryInterface;
use App\Services\Exceptions\Tasks\TaskNotFoundException;
use App\Services\DTO\Tasks\TaskDTO;

class Fetcher
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function fetch(Query $query): TaskDTO
    {
        $task = $this->taskRepository->find($query->id);

        if (!$task) {
            throw new TaskNotFoundException('Задача не найдена');
        }

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