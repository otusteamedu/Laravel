<?php
namespace App\Services\Tasks\Results;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class Fetcher
{
    /**
     *
     * @param LengthAwarePaginator $paginatedTasks
     * @return LengthAwarePaginator
     */
    public function fetch(LengthAwarePaginator $paginatedTasks): LengthAwarePaginator
    {
        $tasks = $paginatedTasks->items();

        $taskDTOs = array_map(function ($task) {
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
