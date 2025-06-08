<?php

namespace App\Services\UseCases\Commands\ProjectUser\Invite;

use App\Models\ProjectRoleEnum;

final readonly class Command
{
    /**
     * @param int $userId
     * @param int $projectId
     * @param ProjectRoleEnum[] $roles
     */
    public function __construct(
        public int   $userId,
        public int   $projectId,
        public array $roles
    ) {}
}
