<?php

namespace App\Services\Repositories;

use App\Models\ProjectRoleEnum;
use App\Services\Repositories\DTOs\ProjectUserDTO;

interface ProjectUserRepositoryInterface
{
    /**
     * Нaйти запись о пользователе в проекте
     * @param int $projectId
     * @param int $userId
     * @return ProjectUserDTO|null
     */
    public function find(int $projectId, int $userId): ?ProjectUserDTO;

    /**
     * Добавить привязку проекта к пользователю
     * @param ProjectUserDTO $projectUser
     * @return int
     */
    public function add(ProjectUserDTO $projectUser): int;

    /**
     * Проверяет есть ли у пользователя нужная роль на проекте
     * @param int $projectId
     * @param int $userId
     * @param ProjectRoleEnum[] $roles
     * @return bool
     */
    public function hasRole(int $projectId, int $userId, array $roles): bool;
}
