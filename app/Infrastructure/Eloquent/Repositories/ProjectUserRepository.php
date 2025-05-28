<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\ProjectUser;
use App\Services\Repositories\ProjectUserRepositoryInterface;

class ProjectUserRepository implements ProjectUserRepositoryInterface
{
    /**
     * Нйти запись о пользователе в проекте
     * @param int $projectId
     * @param int $userId
     * @return void
     */
    public function find(int $projectId, int $userId): ?ProjectUser
    {
        return ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id',  $userId)
            ->first();
    }

    /**
     * Добавить привязку проекта к пользователю
     * @param \App\Models\ProjectUser $projectUser
     * @return void
     */
    public function add(ProjectUser $projectUser): int
    {
        $projectUser->save();
        $projectUser->refresh();

        return $projectUser->id;
    }

    /**
     * Обновить привязку проекта к пользователю
     * @param \App\Models\ProjectUser $projectUser
     * @return void
     */
    public function save(ProjectUser $projectUser): void
    {
        $projectUser->save();
    }

    /**
     * Удалить привязку проекта к пользователю
     * @param \App\Models\ProjectUser $projectUser
     * @return void
     */
    public function destroy(ProjectUser $projectUser): void
    {
        $projectUser->delete();
    }
}
