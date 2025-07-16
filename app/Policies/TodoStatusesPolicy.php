<?php

namespace App\Policies;

use App\Models\User;
use App\Domain\Repositories\Project\ValueObject\ProjectRoleEnum;
use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;

class TodoStatusesPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct(
        private ProjectRepositoryInterface $projecRepository,
    ) {
        //
    }

    public function manage(User $user, int $projectId): bool
    {
        return $this->projecRepository->userHasRole($projectId, $user->id, [ProjectRoleEnum::ADMIN]);
    }
}
