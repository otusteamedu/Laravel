<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\Project;
use App\Models\TodoStatus;
use App\Models\ProjectUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use App\Models\ProjectRoleEnum;
use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\DTOs\TodoStatusDTO;
use App\Services\Repositories\DTOs\ProjectUserDTO;
use App\Services\Repositories\DTOs\InsertTodoStatusesDTO;
use App\Services\Repositories\DTOs\ProjectInvitedUserDTO;
use App\Services\Repositories\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    /**
     * Получить все проекты
     * @return ProjectDTO[]|null
     */
    public function fetchAll(): ?array
    {
        $dbProjects = Project::query()
            ->get();

        if ($dbProjects === null) {
            return null;
        }

        return array_map(
            fn($project) =>
            new ProjectDTO(
                projectId: $project->id,
                name: $project->name,
                description: $project->description,
                created: $project->created_at,
            ),
            Arr::from($dbProjects)
        );
    }

    /**
     * Получить проект по id
     * @param int $id
     * @return ProjectDTO|null
     */
    public function find(int $id): ?ProjectDTO
    {
        $project = Project::query()
            ->where('id', $id)
            ->first();

        if ($project === null) {
            return null;
        }

        return new ProjectDTO(
            projectId: $project->id,
            name: $project->name,
            description: $project->description,
            created: $project->created_at,
        );
    }

    /**
     * Добавить данные проекта
     * @param ProjectDTO $project
     * @return int
     */
    public function add(ProjectDTO $project): int
    {
        $dbProject = Project::create([
            'name'        => $project->name,
            'description' => $project->description,
        ]);

        return $dbProject->refresh()->id;
    }

    /**
     * Обновить данные проекта
     * @param ProjectDTO $project
     * @return bool
     */
    public function save(ProjectDTO $project): bool
    {
        return Project::query()
            ->where('id', $project->projectId)
            ->update([
                'name'        => $project->name,
                'description' => $project->description,
            ]);
    }

    /**
     * Получить пользователей проекта
     * @param int $projectId
     * @return ProjectInvitedUserDTO[]
     */
    public function destroy(int $projectId): bool
    {
        return Project::where('id', $projectId)
            ->delete() ?? false;
    }

    /**
     * Summary of fetchUsers
     * @param int $projectId
     * @return ProjectInvitedUserDTO[]
     */
    public function fetchUsers(int $projectId): array
    {
        $dbUsers = ProjectUser::query()
            ->where('project_id', $projectId)
            ->whereNull('left_at')
            ->join('users', 'users.id', 'project_user.user_id')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'project_user.id',
                'project_user.roles',
                'project_user.invited_at',
                'project_user.joined_at',

            )
            ->orderBy('users.name')
            ->get();

        return array_map(
            fn($dbUser) =>
            new ProjectInvitedUserDTO(
                id: $dbUser->id,
                userId: $dbUser->user_id,
                name: $dbUser->name,
                email: $dbUser->email,
                roles: $dbUser->roles,
                invited: $dbUser->invited_at,
                joined: $dbUser->joined_at,
            ),
            Arr::from($dbUsers)
        );
    }

    /**
     * Нaйти запись о пользователе в проекте
     * @param int $projectId
     * @param int $userId
     * @return ProjectUserDTO|null
     */
    public function findUser(int $projectId, int $userId): ?ProjectUserDTO
    {
        $dbData = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id',  $userId)
            ->whereNull('left_at')
            ->first();

        if ($dbData === null) {
            return null;
        }

        return new ProjectUserDTO(
            id: $dbData->id,
            userId: $dbData->user_id,
            projectId: $dbData->project_id,
            roles: $dbData->roles,
            invited: $dbData->invited_at,
            joined: $dbData->joined_at,
            left: $dbData->left_at,
        );
    }

    /**
     * Пользователя пригласили к участию в проекте
     * @param ProjectUserDTO $projectUser
     * @return int
     */
    public function inviteUser(ProjectUserDTO $projectUser): int
    {
        $dbData = ProjectUser::create([
            'user_id'    => $projectUser->userId,
            'project_id' => $projectUser->projectId,
            'roles'      => $projectUser->roles,
            'invited_at' => $projectUser->invited ?? now(),
            'joined_at'  => $projectUser->joined ?? null,
        ]);

        return $dbData->refresh()->id;
    }

    /**
     * Пользователь вступил в проект
     * @param int $projectId
     * @param int $userId
     * @return bool
     */
    public function joinUser(int $projectId, int $userId): bool
    {
        $updated = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->whereNull('left_at')
            ->update(['joined_at' => now()]);

        return $updated ? true : false;
    }

    /**
     * Пользователь покинул в проект
     * @param int $projectId
     * @param int $userId
     * @return bool
     */
    public function leftUser(int $projectId, int $userId): bool
    {
        $updated = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->whereNull('left_at')
            ->update(['left_at' => now()]);

        return $updated ? true : false;
    }

    /**
     * Все пользователи покинули проект
     * @param int $projectId
     * @return bool
     */
    public function leftAllUsers(int $projectId): bool
    {
        $updated = ProjectUser::query()
            ->where('project_id', $projectId)
            ->whereNotNull('joined_at')
            ->update(['left_at' => now()]);

        return $updated ? true : false;
    }

    /**
     * Проверяет приглашен ли пользователь к участию в проекте
     * @param int $projectId
     * @param int $userId
     * @return bool
     */
    public function userInvited(int $projectId, int $userId): bool
    {
        $dbData = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id',  $userId)
            ->whereNull('left_at')
            ->first();

        return $dbData ? true : false;
    }

    /**
     * Проверяет есть ли у пользователя нужная роль на проекте
     * @param int $projectId
     * @param int $userId
     * @param ProjectRoleEnum[] $roles
     * @return bool
     */
    public function userHasRole(int $projectId, int $userId, array $roles): bool
    {
        $dbData = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id',  $userId)
            ->where(function ($query) use ($roles) {
                $query->whereJsonContains('roles', array_shift($roles)->value);

                foreach ($roles as $role) {
                    $query->orWhereJsonContains('roles', $role->value);
                }
            })
            ->whereNotNull('joined_at')
            ->whereNull('left_at')
            ->first();

        return $dbData ? true : false;
    }

    /**
     * Получить список проектов пользователя
     * @param int $userId
     * @return ProjectDTO[]
     */
    public function fetchUserProjects(int $userId): array
    {
        $dbProjects = Project::query()
            ->whereHas('projectUsers', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereNull('left_at');
            })
            ->with(['projectUsers' => function ($query) use ($userId) {
                $query->where('user_id', $userId)->whereNull('left_at');
            }])
            ->get();

        return array_map(
            fn($project) =>
            new ProjectDTO(
                projectId: $project['id'],
                name: $project['name'],
                description: $project['description'],
                created: Carbon::parse($project['created_at']),
            ),
            $dbProjects->toArray()
        );
    }

    /**
     * Получить статус для задач проекта по id
     * @param int $projectId
     * @param int $statusId
     * @return TodoStatusDTO|null
     */
    public function findTodoStatus(int $projectId, int $statusId): ?TodoStatusDTO
    {
        $status = TodoStatus::query()
            ->where('id', $statusId)
            ->where('project_id', $projectId)
            ->first();

        if ($status === null) {
            return null;
        }

        return new TodoStatusDTO(
            statusId: $status->id,
            projectId: $status->project_id,
            name: $status->name,
            sort: $status->sort,
            color: $status->color,
        );
    }

    /**
     * Добавить данные статуса для задач проекта
     * @param TodoStatusDTO $status
     * @return int
     */
    public function addTodoStatus(TodoStatusDTO $status): int
    {
        $dbStatus = TodoStatus::create([
            'project_id' => $status->projectId,
            'name'       => $status->name,
            'sort'       => $status->sort,
            'color'      => $status->color,
        ]);

        return $dbStatus->refresh()->id;
    }

    /**
     * Обновить данные статуса для задач проекта
     * @param TodoStatusDTO $status
     * @return bool
     */
    public function saveTodoStatus(TodoStatusDTO $status): bool
    {
        $updated = TodoStatus::query()
            ->where('id', $status->statusId)
            ->where('project_id', $status->projectId)
            ->update([
                'name'       => $status->name,
                'sort'       => $status->sort,
                'color'      => $status->color,
            ]);

        return $updated ? true : false;
    }

    /**
     * Удалить статус задач для проекта
     * @param int $projectId
     * @param int $statusId
     * @return bool
     */
    public function destroyTodoStatus(int $projectId, int $statusId): bool
    {
        return TodoStatus::query()
            ->where('id', $statusId)
            ->where('project_id', $projectId)
            ->delete() ?? false;
    }

    /**
     * Массовое добавление статусов в проект
     */
    public function insertTodoStatuses(InsertTodoStatusesDTO $statuses): bool
    {
        $data = array_map(
            fn($status) => [
                'project_id' => $status->projectId,
                'name'       => $status->name,
                'sort'       => $status->sort,
                'color'      => $status->color,
            ],
            $statuses->todoStatusDTOs
        );

        return TodoStatus::insert($data);
    }

    /**
     * Список доступных для задач проекта статусов
     * @param int $projectId
     * @return TodoStatusDTO[]
     */
    public function fetchTodoStatuses(int $projectId): array
    {
        $dbStatuses = TodoStatus::query()
            ->where('project_id', $projectId)
            ->orderBy('sort')
            ->get();

        return array_map(
            fn($status) =>
            new TodoStatusDTO(
                statusId: $status['id'],
                projectId: $status['project_id'],
                name: $status['name'],
                sort: $status['sort'],
                color: $status['color'],
            ),
            $dbStatuses->toArray()
        );
    }
}
