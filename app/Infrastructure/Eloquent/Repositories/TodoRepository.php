<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\Todo;
use App\Services\Repositories\DTOs\TodoDTO;
use App\Services\Repositories\TodoRepositoryInterface;

class TodoRepository implements TodoRepositoryInterface
{
    /**
     * Получить задачу по id
     * @param int $todoId
     * @param int $projectId
     * @return TodoDTO|null
     */
    public function find(int $todoId, int $projectId): ?TodoDTO
    {
        $todo = Todo::query()
            ->where('id', $todoId)
            ->where('project_id', $projectId)
            ->first();

        if ($todo === null) {
            return null;
        }

        return new TodoDTO(
            todoId: $todo->id,
            title: $todo->title,
            authorId: $todo->author_id,
            projectId: $todo->project_id,
            statusId: $todo->status_id,
            description: $todo->description,
            deadline: $todo->deadline,
            created: $todo->created_at,
            updated: $todo->updated_at,
            options: $todo->options,
        );
    }

    /**
     * Добавить задачу
     * @param TodoDTO $todo
     * @return int
     */
    public function add(TodoDTO $todo): int
    {
        $todo = Todo::create([
            'title'       => $todo->title,
            'author_id'   => $todo->authorId,
            'project_id'  => $todo->projectId,
            'status_id'   => $todo->statusId,
            'description' => $todo->description,
            'deadline'    => $todo->deadline,
            'options'     => $todo->options,
        ]);

        return $todo->refresh()->id;
    }

    /**
     * Обновить задачу проекта
     * @param TodoDTO $todo
     * @return bool
     */
    public function save(TodoDTO $todo): bool
    {
        return Todo::query()
            ->where('id', $todo->todoId)
            ->where('project_id', $todo->projectId)
            ->update([
                'title'       => $todo->title,
                'author_id'   => $todo->authorId,
                'status_id'   => $todo->statusId,
                'description' => $todo->description,
                'deadline'    => $todo->deadline,
                'options'     => $todo->options,
            ]);
    }

    /**
     * Удалить задачу
     * @param int $todoId
     * @param int $projectId
     * @return bool
     */
    public function destroy(int $todoId, int $projectId): bool
    {
        return Todo::query()
            ->where('id', $todoId)
            ->where('project_id', $projectId)
            ->delete() ?? false;
    }
}
