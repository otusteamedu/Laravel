<?php

namespace App\Application\UseCases\Commands\ProjectUser\Invite;

use App\Domain\Repositories\Project\ValueObject\ProjectRoleEnum;

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
