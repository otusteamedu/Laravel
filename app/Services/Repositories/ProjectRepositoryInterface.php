<?php

namespace App\Services\Repositories;

use App\Models\ProjectRoleEnum;
use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\DTOs\TodoStatusDTO;
use App\Services\Repositories\DTOs\ProjectUserDTO;
use App\Services\Repositories\DTOs\InsertTodoStatusesDTO;
use App\Services\Repositories\DTOs\ProjectInvitedUserDTO;

interface ProjectRepositoryInterface
{
    /**
     * Получить все проекты
     * @return ProjectDTO[]|null
     */
    public function fetchAll(): ?array;

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
     * @param int $projectId
     * @return ProjectInvitedUserDTO[]
     */
    public function fetchUsers(int $projectId): array;

    /**
     * Нaйти запись о пользователе в проекте
     * @param int $projectId
     * @param int $userId
     * @return ProjectUserDTO|null
     */
    public function findUser(int $projectId, int $userId): ?ProjectUserDTO;

    /**
     * Пользователя пригласили к участию в проекте
     * @param ProjectUserDTO $projectUser
     * @return int
     */
    public function inviteUser(ProjectUserDTO $projectUser): int;

    /**
     * Пользователь вступил в проект
     * @param int $projectId
     * @param int $userId
     * @return bool
     */
    public function joinUser(int $projectId, int $userId): bool;

    /**
     * Пользователь покинул в проект
     * @param int $projectId
     * @param int $userId
     * @return bool
     */
    public function leftUser(int $projectId, int $userId): bool;

    /**
     * Все пользователи покинули проект
     * @param int $projectId
     * @return bool
     */
    public function leftAllUsers(int $projectId): bool;

    /**
     * Проверяет приглашен ли пользователь к участию в проекте
     * @param int $projectId
     * @param int $userId
     * @return bool
     */
    public function userInvited(int $projectId, int $userId): bool;

    /**
     * Проверяет есть ли у пользователя нужная роль на проекте
     * @param int $projectId
     * @param int $userId
     * @param ProjectRoleEnum[] $roles
     * @return bool
     */
    public function userHasRole(int $projectId, int $userId, array $roles): bool;

    /**
     * Получить список проектов пользователя
     * @param int $userId
     * @return ProjectDTO[]
     */
    public function fetchUserProjects(int $userId): array;

    /**
     * Получить статус для задач проекта по id
     * @param int $projectId
     * @param int $statusId
     * @return TodoStatusDTO|null
     */
    public function findTodoStatus(int $projectId, int $statusId): ?TodoStatusDTO;

    /**
     * Добавить данные статуса для задач проекта
     * @param TodoStatusDTO $status
     * @return int
     */
    public function addTodoStatus(TodoStatusDTO $status): int;

    /**
     * Обновить данные статуса для задач проекта
     * @param TodoStatusDTO $status
     * @return bool
     */
    public function saveTodoStatus(TodoStatusDTO $status): bool;

    /**
     * Удалить статус задач для проекта
     * @param int $projectId
     * @param int $statusId
     * @return bool
     */
    public function destroyTodoStatus(int $projectId, int $statusId): bool;

    /**
     * Массовое добавление статусов в проект
     */
    public function insertTodoStatuses(InsertTodoStatusesDTO $statuses): bool;

    /**
     * Список доступных для задач проекта статусов
     * @param int $projectId
     * @return TodoStatusDTO[]
     */
    public function fetchTodoStatuses(int $projectId): array;
}
