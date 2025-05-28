<?php

namespace App\Services\Repositories;

use App\Models\ProjectUser;

interface ProjectUserRepositoryInterface
{
    /**
     * Нйти запись о пользователе в проекте
     * @param int $projectId
     * @param int $userId
     * @return void
     */
    public function find(int $projectId, int $userId): ?ProjectUser;

    /**
     * Добавить привязку проекта к пользователю
     * @param \App\Models\ProjectUser $projectUser
     * @return int
     */
    public function add(ProjectUser $projectUser): int;

    /**
     * Обновить привязку проекта к пользователю
     * @param \App\Models\ProjectUser $projectUser
     * @return void
     */
    public function save(ProjectUser $projectUser): void;

    /**
     * Удалить привязку проекта к пользователю
     * @param \App\Models\ProjectUser $projectUser
     * @return void
     */
    public function destroy(ProjectUser $projectUser): void;
}
