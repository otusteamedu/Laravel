<?php

namespace App\Services\Repositories;

use App\Services\Repositories\DTOs\TodoDTO;

interface TodoRepositoryInterface
{
    /**
     * Получить задачу по id
     * @param int $todoId
     * @param int $projectId
     * @return TodoDTO|null
     */
    public function find(int $todoId, int $projectId): ?TodoDTO;

    /**
     * Добавить задачу
     * @param TodoDTO $todo
     * @return int
     */
    public function add(TodoDTO $todo): int;

    /**
     * Обновить задачу проекта
     * @param TodoDTO $project
     * @return bool
     */
    public function save(TodoDTO $todo): bool;

    /**
     * Удалить задачу
     * @param int $todoId
     * @param int $projectId
     * @return bool
     */
    public function destroy(int $todoId, int $projectId): bool;
}
