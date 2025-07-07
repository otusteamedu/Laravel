<?php

namespace App\TodoApp\Domain\Repositories;

use App\TodoApp\Domain\Models\Project;
use App\TodoApp\Domain\Models\TodoStatus;
use App\TodoApp\Application\DTOs\ProjectUserDTO;
use App\TodoApp\Domain\ValueObjects\ProjectRoleEnum;
use App\TodoApp\Application\DTOs\ProjectInvitedUserDTO;

interface ProjectRepositoryInterface
{
    /**
     * Получить максимальный PK Id
     * @return int
     */
    public function getNextId(): int;

    /**
     * Получить все проекты
     * @return Project[]|null
     */
    public function fetchAll(): ?array;

    /**
     * Получить проект по id
     * @param int $id
     * @return Project|null
     */
    public function find(int $id): ?Project;

    /**
     * Добавить данные проекта
     * @param Project $project
     * @return int
     */
    public function add(Project $project): int;

    /**
     * Обновить данные проекта
     * @param Project $project
     * @return bool
     */
    public function save(Project $project): bool;

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
     * @return Project[]
     */
    public function fetchUserProjects(int $userId): array;

    /**
     * Получить максимальный PK Id
     * @return int
     */
    public function getTodoStatusNextId(): int;

    /**
     * Получить статус для задач проекта по id
     * @param int $projectId
     * @param int $statusId
     * @return TodoStatus|null
     */
    public function findTodoStatus(int $projectId, int $statusId): ?TodoStatus;

    /**
     * Добавить данные статуса для задач проекта
     * @param TodoStatus $status
     * @return int
     */
    public function addTodoStatus(TodoStatus $status): int;

    /**
     * Обновить данные статуса для задач проекта
     * @param TodoStatus $status
     * @return bool
     */
    public function saveTodoStatus(TodoStatus $status): bool;

    /**
     * Удалить статус задач для проекта
     * @param int $projectId
     * @param int $statusId
     * @return bool
     */
    public function destroyTodoStatus(int $projectId, int $statusId): bool;

    /**
     * Список доступных для задач проекта статусов
     * @param int $projectId
     * @return TodoStatus[]
     */
    public function fetchTodoStatuses(int $projectId): array;
}
