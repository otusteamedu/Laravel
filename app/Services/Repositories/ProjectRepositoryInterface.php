<?php

namespace App\Services\Repositories;

use App\Services\Repositories\DTOs\ProjectDTO;

interface ProjectRepositoryInterface
{
    /**
     * Получить проект по id
     * @param int $id
     * @return ProjectDTO|null
     */
    public function find(int $id): ?ProjectDTO;

    /**
     * Добавить данные проекта
     * @param ProjectDTO $project
     * @return int
     */
    public function add(ProjectDTO $project): int;

    /**
     * Обновить данные проекта
     * @param ProjectDTO $project
     * @return bool
     */
    public function save(ProjectDTO $project): bool;

    /**
     * Удалить проект
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool;
}
