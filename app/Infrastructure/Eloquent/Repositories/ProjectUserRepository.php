<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\DTOs\ProjectUserDTO;
use App\Services\Repositories\DTOs\ProjectInvitedUserDTO;
use App\Services\Repositories\ProjectUserRepositoryInterface;

class ProjectUserRepository implements ProjectUserRepositoryInterface
{
    public function find(int $projectId, int $userId): ?ProjectUserDTO
    {
        $dbData = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id',  $userId)
            ->first();

        return new ProjectUserDTO(
            id: $dbData->id,
            user_id: $dbData->user_id,
            project_id: $dbData->project_id,
            roles: $dbData->roles,
            invited: $dbData->invited_at,
            joined: $dbData->joined_at,
            left: $dbData->left_at,
        );
    }

    public function add(ProjectUserDTO $projectUser): int
    {
        $dbData = ProjectUser::create([
            'user_id'    => $projectUser->user_id,
            'project_id' => $projectUser->project_id,
            'roles'      => $projectUser->roles,
            'invited_at' => $projectUser->invited ?? now(),
            'joined_at'  => $projectUser->joined,
        ]);

        return $dbData->refresh()->id;
    }

    public function hasRole(int $projectId, int $userId, array $roles): bool
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

    public function userJoin(int $projectId, int $userId): bool
    {
        $updated = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->whereNull('left_at')
            ->update(['joined_at' => now()]);

        return $updated ? true : false;
    }

    public function userLeft(int $projectId, int $userId): bool
    {
        $updated = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->whereNotNull('joined_at')
            ->whereNull('left_at')
            ->update(['left_at' => now()]);

        return $updated ? true : false;
    }

    public function usersLeft(int $projectId): bool
    {
        $updated = ProjectUser::query()
            ->where('project_id', $projectId)
            ->whereNotNull('joined_at')
            ->update(['left_at' => now()]);

        return $updated ? true : false;
    }

    /**
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
}
