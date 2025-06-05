<?php

namespace App\Services\Repositories;

use App\Models\ProjectRoleEnum;
use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\DTOs\ProjectUserDTO;
use App\Services\Repositories\DTOs\ProjectInvitedUserDTO;

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
     * Пользователя пригласили к участию в проекте
     * @param ProjectUserDTO $projectUser
     * @return int
     */
    public function add(ProjectUserDTO $projectUser): int;

    /**
     * Пользователь вступил в проект
     * @param int $projectId
     * @param int $userId
     * @return bool
     */
    public function userJoin(int $projectId, int $userId): bool;

    /**
     * Пользователь покинул в проект
     * @param int $projectId
     * @param int $userId
     * @return bool
     */
    public function userLeft(int $projectId, int $userId): bool;

    /**
     * Все пользователи покинули проект
     * @param int $projectId
     * @return bool
     */
    public function usersLeft(int $projectId): bool;

    /**
     * Проверяет есть ли у пользователя нужная роль на проекте
     * @param int $projectId
     * @param int $userId
     * @param ProjectRoleEnum[] $roles
     * @return bool
     */
    public function hasRole(int $projectId, int $userId, array $roles): bool;

    /**
     * Получить список проектов пользователя
     * @param int $userId
     * @return ProjectDTO[]
     */
    public function fetchForUser(int $userId): array;

    /**
     * Получить пользователей проекта
     * @param int $projectId
     * @return ProjectInvitedUserDTO[]
     */
    public function fetchUsers(int $projectId): array;
}
