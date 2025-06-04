<?php

namespace App\Services\Repositories;

use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\DTOs\ProjectInvitedUserDTO;

interface ProjectRepositoryInterface
{
    /**
     * Получить список проектов пользователя
     * @return ProjectDTO[]
     */
    public function fetchForUser(int $userId): array;

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

    /**
     * Получить пользователей проекта
     * @param int $id
     * @return ProjectInvitedUserDTO[]
     */
    public function fetchUsers(int $projectId): array;

    /**
     * Пользователь вступил в проект
     */
    public function userJoun(int $userId): bool;

    /**
     * Все пользователи покинули проект
     */
    public function usersLeft(int $id): bool;
}
