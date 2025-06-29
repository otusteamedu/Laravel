<?php

namespace App\Services\Repositories\Todo;

use App\Models\TodoRoleEnum;
use App\Services\Repositories\Todo\TodoDTO;
use App\Services\Repositories\Todo\TodoUserDTO;
use App\Services\Repositories\Todo\TodoFetchDTO;
use App\Services\Repositories\Todo\TodoCommentDTO;

interface TodoRepositoryInterface
{
    /**
     * Получить задачу по id
     * @param int $todoId
     * @param int $projectId
     * @return TodoFetchDTO|null
     */
    public function find(int $todoId, int $projectId): ?TodoFetchDTO;

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

    /**
     * Получить список задач проекта
     * Если передан userId, то будут возвращенны только задачи в которых пользователь учавствует
     * @param int $projectId
     * @param int|null $userId
     * @return TodoFetchDTO[]
     */
    public function fetchForProject(int $projectId, ?int $userId = null): array;

    /**
     * Найти пользователя среди участников задачи
     * @param int $todoId
     * @param int $userId
     * @return TodoUserDTO|null
     */
    public function findUser(int $todoId, int $userId): ?TodoUserDTO;

    /**
     * Добавить к задаче участника или измениь его роль
     * @param int $todoId
     * @param int $userId
     * @param TodoRoleEnum $role
     * @return bool
     */
    public function saveUser(int $todoId, int $userId, TodoRoleEnum $role): bool;

    /**
     * Удалить участника из задачи
     * @param int $todoId
     * @param int $userId
     * @return bool
     */
    public function renoveUser(int $todoId, int $userId): bool;

    /**
     * Получить список участников задачи
     * @param int $todoId
     * @return TodoUserDTO[]
     */
    public function fetchUsers(int $todoId): array;

    /**
     * Получить список участников задачи с определенной ролью
     * @param int $todoId
     * @param TodoRoleEnum $role
     * @return TodoUserDTO[]
     */
    public function fetchUsersByRole(int $todoId, TodoRoleEnum $role): array;

    /**
     * Проверить наличие роли у уастника
     * @param int $todoId
     * @param int $userId
     * @param TodoRoleEnum $role
     * @return bool
     */
    public function userHasRole(int $todoId, int $userId, TodoRoleEnum $role): bool;

    /**
     * Найти комментарий
     * @param int $commentId
     * @param int $todoId
     * @return TodoCommentDTO|null
     */
    public function findComment(int $commentId, int $todoId): ?TodoCommentDTO;

    /**
     * Добавить комментраий к задаче
     * @param TodoCommentDTO $comment
     * @return int
     */
    public function addComment(TodoCommentDTO $comment): int;

    /**
     * Обновить комментарий к задаче
     * @param TodoCommentDTO $comment
     * @return bool
     */
    public function saveComment(TodoCommentDTO $comment): bool;

    /**
     * Удалить комментарий к задаче
     * @param int $commentId
     * @param int $todoId
     * @return bool
     */
    public function destroyComment(int $commentId, int $todoId): bool;

    /**
     * Получить список комменатриев задачи
     * @param int $todoId
     * @return TodoCommentDTO[]
     */
    public function fetchComments(int $todoId): array;
}
