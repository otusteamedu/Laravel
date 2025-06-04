<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\ProjectUser;
use App\Models\ProjectRoleEnum;
use App\Services\Repositories\DTOs\ProjectUserDTO;
use App\Services\Repositories\ProjectUserRepositoryInterface;

class ProjectUserRepository implements ProjectUserRepositoryInterface
{
    /**
     * Нaйти запись о пользователе в проекте
     * @param int $projectId
     * @param int $userId
     * @return ProjectUserDTO|null
     */
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

    /**
     * Добавить привязку проекта к пользователю
     * @param ProjectUserDTO $projectUser
     * @return int
     */
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

    /**
     * Проверяет есть ли у пользователя нужная роль на проекте
     * @param int $projectId
     * @param int $userId
     * @param ProjectRoleEnum[] $roles
     * @return bool
     */
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
}
