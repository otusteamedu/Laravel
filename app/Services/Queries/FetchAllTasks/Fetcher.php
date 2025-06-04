<?php

namespace App\Services\Queries\FetchAllTasks;

use App\Repositories\Tasks\TaskRepositoryInterface;
use App\Services\DTO\Tasks\TaskDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class Fetcher
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function fetch(Query $query): LengthAwarePaginator
    {
        $paginatedTasks = $this->taskRepository->getAllPaginated($query->perPage);
        $tasks = $paginatedTasks->items();

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

        $paginator = new LengthAwarePaginator(
            $taskDTOs,
            $paginatedTasks->total(),
            $paginatedTasks->perPage(),
            $paginatedTasks->currentPage(),
            ['path' => $paginatedTasks->path()]
        );

        return $paginator->withQueryString();
    }
} 