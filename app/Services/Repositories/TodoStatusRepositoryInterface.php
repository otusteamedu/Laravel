<?php

namespace App\Services\Repositories;

use App\Models\Project;
use App\Models\TodoStatus;
use App\Services\UseCases\Commands\TodoStatus\Insert\Command;

interface TodoStatusRepositoryInterface
{
    /**
     * Получить статус по id
     * @param int $id
     * @return \App\Models\TodoStatus|null
     */
    public function find(int $id): ?TodoStatus;

    /**
     * Получить статус по id с учетом проекта
     * @param int $id
     * @param int $projectId
     * @return \App\Models\TodoStatus|null
     */
    public function findWithProject(int $id, int $projectId): ?TodoStatus;


    /**
     * Добавить данные статуса для задач проекта
     * @param \App\Models\TodoStatus $status
     * @return int
     */
    public function add(TodoStatus $status): int;

    /**
     * Обновить данные статуса для задач проекта
     * @param \App\Models\TodoStatus $status
     * @return void
     */
    public function save(TodoStatus $status): void;

    /**
     * Удалить статус задач для проекта
     * @param \App\Models\TodoStatus $status
     * @return void
     */
    public function destroy(TodoStatus $status): void;

    /**
     * Массовое добавление статусов в проект
     */
    public function insert(InsertTodoStatusesDTO $todoStatusesDTO): bool;
}
