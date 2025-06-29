<?php

namespace App\Services\UseCases\Commands\Todo\UserRole;

use App\Models\TodoRoleEnum;


final readonly class Command
{
    /**
     * @param int $userId
     * @param int $projectId
     * @param int $todoId
     * @param TodoRoleEnum $role
     */
    public function __construct(
        public int $userId,
        public int $projectId,
        public int $todoId,
        public TodoRoleEnum $role,
    ) {}
}
