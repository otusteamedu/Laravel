<?php

namespace App\Services\Repositories;

interface TodoStatusRepositoryInterface
{
    /**
     * Получить статус по id
     * @param int $id
     * @return \App\Models\TodoStatus|null
     */
    public function find(int $id): ?TodoStatusDTO;

    /**
     * Добавить данные статуса для задач проекта
     * @param TodoStatusDTO $status
     * @return int
     */
    public function add(TodoStatusDTO $status): int;

    /**
     * Обновить данные статуса для задач проекта
     * @param TodoStatusDTO $status
     * @return bool
     */
    public function save(TodoStatusDTO $status): bool;

    /**
     * Удалить статус задач для проекта
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool;

    /**
     * Массовое добавление статусов в проект
     */
    public function insert(InsertTodoStatusesDTO $todoStatusesDTO): bool;
}
