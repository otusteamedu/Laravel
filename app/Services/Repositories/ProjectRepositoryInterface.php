<?php

namespace App\Services\Repositories;

use App\Models\Project;

interface ProjectRepositoryInterface
{
    /**
     * Получить список проектов пользователя
     * @return \App\Models\Project[]
     */
    public function fetchForUser(int $userId): array;

    /**
     * Получить проект по id
     * @param int $id
     * @return \App\Models\Project|null
     */
    public function find(int $id): ?Project;

    /**
     * Добавить данные проекта
     * @param \App\Models\Project $project
     * @return int
     */
    public function add(Project $project): int;

    /**
     * Обновить данные проекта
     * @param \App\Models\Project $project
     * @return void
     */
    public function save(Project $project): void;

    /**
     * Удалить проект
     * @param \App\Models\Project $project
     * @return void
     */
    public function destroy(Project $project): void;

    /**
     * Получить пользователей проекта
     * @param int $projectId
     * @return \App\Models\ProjectUser[]
     */
    public function fetchUsers(int $projectId): array;

    /**
     * Получить статусы задач для проекта
     * @param int $projectId
     * @return \App\Models\TodoStatus[]
     */
    public function fetchTodoStatuses(int $projectId): array;
}
