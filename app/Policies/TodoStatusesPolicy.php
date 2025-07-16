<?php

namespace App\Policies;

use App\Models\User;
use App\TodoApp\Domain\ValueObjects\ProjectRoleEnum;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;

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
