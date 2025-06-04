<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Services\Repositories\ProjectRepositoryInterface;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Support\Arr;
use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\DTOs\ProjectInvitedUserDTO;
use App\Services\Repositories\Exceptions\MethodNotImplimentedException;


class ProjectRepository implements ProjectRepositoryInterface
{
    /**
     * Получить список проектов пользователя
     * @return ProjectDTO[]
     */
    public function fetchForUser(int $userId): array
    {
        $dbProjects = Project::query()
            ->whereHas('projectUsers', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereNotNull('joined_at')
                    ->whereNull('left_at');
            })
            ->get();

        return array_map(
            fn($project) =>
            new ProjectDTO(
                id: $project['id'],
                name: $project['name'],
                description: $project['description'],
                created: Carbon::parse($project['created_at']),
            ),
            $dbProjects->toArray()
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
            id: $project->id,
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
            ->where('id', $project->id)
            ->update([
                'name'        => $project->name,
                'description' => $project->description,
            ]);
    }

    /**
     * Удалить проект
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        return Project::where('id', $id,)
            ->delete() ?? false;
    }

    /**
     * Получить пользователей проекта
     * @param int $id
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
            fn($user) =>
            new ProjectInvitedUserDTO(
                id: $user->id,
                user_id: $user->user_id,
                name: $user->name,
                email: $user->email,
                roles: $user->roles,
                invited: $user->invited_at,
                joined: $user->joined_at,
            ),
            Arr::from($dbUsers)
        );
    }

    /**
     * Пользователь вступил в проект
     */
    public function userJoun(int $userId): bool
    {
        throw new MethodNotImplimentedException;
    }

    /**
     * Все пользователи покинули проект
     */
    public function usersLeft(int $id): bool
    {
        return ProjectUser::query()
            ->where('project_id', $id)
            ->whereNotNull('joined_at')
            ->update(['left_at' => now()]);
    }
}
