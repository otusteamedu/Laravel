<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\ProjectUser;
use App\Services\Repositories\ProjectUserDTO;
use App\Services\Repositories\ProjectUserRepositoryInterface;

class ProjectUserRepository implements ProjectUserRepositoryInterface
{
    /**
     * Нйти запись о пользователе в проекте
     * @param int $projectId
     * @param int $userId
     * @return void
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
            roles: json_decode($dbData->roles),
            invited: $dbData->invited_at,
            joined: $dbData->joined_at,
            left: $dbData->left_at,
        );
    }

    /**
     * Добавить привязку проекта к пользователю
     * @param ProjectUserDTO $projectUser
     * @return void
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
}
