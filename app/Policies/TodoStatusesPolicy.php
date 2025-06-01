<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProjectRoleEnum;
use App\Services\Repositories\ProjectUserRepository;

class TodoStatusesPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct(
        private ProjectUserRepository $projectUserRepository,
    ) {
        //
    }

    public function list(User $user, int $projectId): bool
    {
        return $this->projectUserRepository->hasRole($projectId, $user->id, [ProjectRoleEnum::ADMIN, ProjectRoleEnum::MEMBER]);
    }

    public function manage(User $user, int $projectId): bool
    {
        return $this->projectUserRepository->hasRole($projectId, $user->id, [ProjectRoleEnum::ADMIN]);
    }
}
