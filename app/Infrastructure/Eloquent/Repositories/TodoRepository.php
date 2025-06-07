<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\Todo;
use App\Models\User;
use App\Models\TodoUser;
use App\Models\TodoComment;
use App\Models\TodoRoleEnum;
use App\Services\Repositories\DTOs\UserDTO;
use App\Services\Repositories\Todo\TodoDTO;
use App\Services\Repositories\Todo\TodoUserDTO;
use App\Services\Repositories\Todo\TodoFetchDTO;
use App\Services\Repositories\DTOs\TodoStatusDTO;
use App\Services\Repositories\Todo\TodoCommentDTO;
use App\Services\Repositories\Todo\TodoRepositoryInterface;
use Carbon\Carbon;

class TodoRepository implements TodoRepositoryInterface
{
    /**
     * Получить задачу по id
     * @param int $todoId
     * @param int $projectId
     * @return TodoFetchDTO|null
     */
    public function find(int $todoId, int $projectId): ?TodoFetchDTO
    {
        $todo = Todo::query()
            ->where('id', $todoId)
            ->where('project_id', $projectId)
            ->with('author')
            ->with('status')
            ->first();

        if ($todo === null) {
            return null;
        }

        return new TodoFetchDTO(
            todoId: $todo->id,
            title: $todo->title,
            author: new UserDTO(
                userId: $todo->author->id,
                name: $todo->author->name,
                email: $todo->author->email,
            ),
            status: new TodoStatusDTO(
                projectId: $projectId,
                name: $todo->status->name,
                sort: $todo->status->sort,
                color: $todo->status->color,
                statusId: $todo->status_id
            ),
            description: $todo->description,
            deadline: $todo->deadline,
            created: $todo->created_at,
            updated: $todo->updated_at,
            options: $todo->options,
        );
    }

    /**
     * Добавить задачу
     * @param TodoDTO $todo
     * @return int
     */
    public function add(TodoDTO $todo): int
    {
        $todo = Todo::create([
            'title'       => $todo->title,
            'author_id'   => $todo->authorId,
            'project_id'  => $todo->projectId,
            'status_id'   => $todo->statusId,
            'description' => $todo->description,
            'deadline'    => $todo->deadline,
            'options'     => $todo->options,
        ]);

        return $todo->refresh()->id;
    }

    /**
     * Обновить задачу проекта
     * @param TodoDTO $todo
     * @return bool
     */
    public function save(TodoDTO $todo): bool
    {
        return Todo::query()
            ->where('id', $todo->todoId)
            ->where('project_id', $todo->projectId)
            ->update([
                'title'       => $todo->title,
                'author_id'   => $todo->authorId,
                'status_id'   => $todo->statusId,
                'description' => $todo->description,
                'deadline'    => $todo->deadline,
                'options'     => $todo->options,
            ]);
    }

    /**
     * Удалить задачу
     * @param int $todoId
     * @param int $projectId
     * @return bool
     */
    public function destroy(int $todoId, int $projectId): bool
    {
        return Todo::query()
            ->where('id', $todoId)
            ->where('project_id', $projectId)
            ->delete() ?? false;
    }

    /**
     * Получить список задач проекта
     * Если передан userId, то будут возвращенны только задачи в которых пользователь учавствует
     * @param int $projectId
     * @param int|null $userId
     * @return TodoFetchDTO[]
     */
    public function fetch(int $projectId, int $userId = null): array
    {
        $query = Todo::query();

        if ($userId) {
            $user = User::where('id', $userId)->firstOrNew();
            $query->member($user);
        }

        $query->where('project_id', $projectId)
            ->with('author')
            ->with('status')
            ->orderBy('deadline');

        $dbTodos = $query->get();

        return array_map(
            fn($todo) => new TodoFetchDTO(
                todoId: $todo['id'],
                title: $todo['title'],
                author: new UserDTO(
                    userId: $todo['author']['id'],
                    name: $todo['author']['name'],
                    email: $todo['author']['email'],
                ),
                status: new TodoStatusDTO(
                    projectId: $projectId,
                    name: $todo['status']['name'],
                    sort: $todo['status']['sort'],
                    color: $todo['status']['color'],
                    statusId: $todo['status_id']
                ),
                description: $todo['description'],
                deadline: Carbon::parse($todo['deadline']),
                created: Carbon::parse($todo['created_at']),
                updated: Carbon::parse($todo['updated_at']),
                options: $todo['options'],
            ),
            $dbTodos->toArray()
        );
    }

    /**
     * Получить список комменатриев задачи
     * @param int $todoId
     * @param int $projectId
     * @return TodoCommentDTO[]
     */
    public function fetchComments(int $todoId, int $projectId): array
    {
        $dbComments = TodoComment::query()
            ->where('todo_id', $todoId)
            ->where('project_id', $projectId)
            ->orderBy('created_at')
            ->get();

        return array_map(
            fn($comment) =>
            new TodoCommentDTO(
                commentId: $comment['id'],
                todoId: $comment['todo_id'],
                authorId: $comment['aiuthor_id'],
                comment: $comment['comment'],
                created: $comment['created_at'],
                updated: $comment['updated_at'],

            ),
            $dbComments->toArray()
        );
    }

    /**
     * Получить список участников задачи
     * @param int $todoId
     * @param int $projectId
     * @return TodoUserDTO[]
     */
    public function fetchUsers(int $todoId): array
    {
        $dbData = TodoUser::query()
            ->where('todo_id', $todoId)
            ->join('users', 'users.id', 'todo_user.user_id')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'todo_user.id',
                'todo_user.roles',
            )
            ->orderBy('users.name')
            ->get();

        return array_map(fn($user) => new TodoUserDTO(
            id: $user->id,
            userId: $user->user_id,
            name: $user->name,
            email: $user->emsil,
            role: $user->role
        ), $dbData->toArray());
    }

    /**
     * Добавить к задаче участника или измениь его роль
     * @param int $todoId
     * @param int $userId
     * @param TodoRoleEnum $role
     * @return bool
     */
    public function userRole(int $todoId, int $userId, TodoRoleEnum $role): bool
    {
        $dbUser = TodoUser::updateOrCreate(
            [
                'todo_id' => $todoId,
                'user_id' =>  $userId,
            ],
            [
                'roles' => [$role]
            ]
        );

        return $dbUser ? true : false;
    }

    /**
     * Удалить участника из задачи
     * @param int $todoId
     * @param int $userId
     * @return bool
     */
    public function renoveUser(int $todoId, int $userId): bool
    {
        return TodoUser::query()
            ->where('todo_id', $todoId)
            ->where('user_id',  $userId)
            ->delete() ?? false;
    }

    /**
     * Проверить наличие роли у уастника
     * @param int $todoId
     * @param int $userId
     * @param TodoRoleEnum $role
     * @return bool
     */
    public function userHasRole(int $todoId, int $userId, TodoRoleEnum $role): bool
    {
        $dbData = TodoUser::query()
            ->where('todo_id', $todoId)
            ->where('user_id',  $userId)
            ->where(function ($query) use ($role) {
                $query->whereJsonContains('roles', $role);
            })
            ->first();

        return $dbData ? true : false;
    }

    /**
     * Найти комментарий
     * @param int $commentId
     * @param int $todoId
     * @return void
     */
    public function findComment(int $commentId, int $todoId): ?TodoCommentDTO
    {
        $comment = TodoComment::query()
            ->where('id', $commentId)
            ->where('todo_id', $todoId)
            ->first();

        if ($comment === null) {
            return null;
        }

        return new TodoCommentDTO(
            commentId: $comment->id,
            todoId: $comment->todo_id,
            authorId: $comment->author_id,
            comment: $comment->comment,
            created: $comment->created_at,
            updated: $comment->updated_at,
        );
    }

    /**
     * Добавить комментраий к задаче
     * @param TodoCommentDTO $comment
     * @return void
     */
    public function addComment(TodoCommentDTO $comment): int
    {
        $comment = TodoComment::create([
            'todo_id'   => $comment->todoId,
            'author_id' => $comment->authorId,
            'comment'   => $comment->comment,
        ]);

        return $comment->refresh()->id;
    }

    /**
     * Обновить комментарий к задаче
     * @param TodoCommentDTO $comment
     * @return void
     */
    public function saveComment(TodoCommentDTO $comment): bool
    {
        return TodoComment::query()
            ->where('id', $comment->commentId)
            ->where('todo_id', $comment->todoId)
            ->update([
                'comment'   => $comment->comment,
            ]);
    }

    /**
     * Удалить комментарий к задаче
     * @param int $commentId
     * @param int $todoId
     * @return void
     */
    public function destroyComment(int $commentId, int $todoId): bool
    {
        return TodoComment::query()
            ->where('id', $commentId)
            ->where('todo_id', $todoId)
            ->delete() ?? false;
    }
}
