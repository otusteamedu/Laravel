<?php

namespace App\Services\Queries\FetchAllTasks;

use App\Repositories\Tasks\TaskRepositoryInterface;
use App\Services\DTO\Tasks\TaskDTO;
use App\Services\DTO\Tasks\PaginatedResult;

class Fetcher
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function fetch(Query $query): PaginatedResult
    {
        $tasks = $this->taskRepository->fetchPaginated($query->limit, $query->offset);
        $total = $this->taskRepository->count();

        $taskDTOs = array_map(function ($task) {
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
                status: $task->status ?? 'новая',
                dueDate: $task->due_date,
                createdAt: $task->created_at,
                updatedAt: $task->updated_at,
            );
        }, $tasks);

        return new PaginatedResult(
            items: $taskDTOs,
            total: $total,
            limit: $query->limit,
            offset: $query->offset
        );
    }
} 