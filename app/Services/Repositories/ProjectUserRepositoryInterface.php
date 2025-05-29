<?php

namespace App\Services\Repositories;


interface ProjectUserRepositoryInterface
{
    /**
     * Нйти запись о пользователе в проекте
     * @param int $projectId
     * @param int $userId
     * @return void
     */
    public function find(int $projectId, int $userId): ?ProjectUserDTO;

    /**
     * Добавить привязку проекта к пользователю
     * @param ProjectUserDTO $projectUser
     * @return int
     */
    public function add(ProjectUserDTO $projectUser): int;
}
