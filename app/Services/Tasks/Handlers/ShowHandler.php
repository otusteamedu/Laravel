<?php
namespace App\Services\Tasks\Handlers;

use App\Services\Tasks\Exceptions\TaskNotFoundException;
use App\Services\Tasks\Results\TaskDTO;
use App\Repositories\Tasks\TaskRepositoryInterface;

class ShowHandler
{
    public function __construct(private TaskRepositoryInterface $taskRepository)
    {
    }

    /**
     * @param int $id
     *
     * @return TaskDTO
     * @throws TaskNotFoundException
     */
    public function __invoke(int $id): TaskDTO {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            throw new TaskNotFoundException('Задача не найдена');
        }

        return new TaskDTO(
            id: $task->id,
            title: $task->title,
            description: $task->description,
            executor_id: $task->executor_id,
            executor_name: $task->executor ? $task->executor->name : '',
            category_id: $task->category_id,
            category_name: $task->category ? $task->category->name : '',
            category_color: $task->category ? $task->category->color : '',
            priority_id: $task->priority_id,
            priority_name: $task->priority ? $task->priority->name : '',
            due_date: $task->due_date ? $task->due_date->format('Y-m-d H:i:s') : null,
            created_at: $task->created_at ? $task->created_at->format('Y-m-d H:i:s') : null,
            updated_at: $task->updated_at ? $task->updated_at->format('Y-m-d H:i:s') : null,
        );
    }
} 