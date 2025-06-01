<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProjectRoleEnum;
use App\Services\Repositories\ProjectUserRepository;

class ProjectPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct(
        private ProjectUserRepository $projectUserRepository,
    ) {
        //
    }

    public function create(User $user)
    {
        return !empty($user);
    }

    public function view(User $user, int $projectId): bool
    {
        return $this->projectUserRepository->hasRole($projectId, $user->id, [ProjectRoleEnum::ADMIN, ProjectRoleEnum::MEMBER]);
    }

    public function update(User $user, int $projectId): bool
    {
        return $this->projectUserRepository->hasRole($projectId, $user->id, [ProjectRoleEnum::ADMIN]);
    }

    public function delete(User $user, int $projectId): bool
    {
        return $this->projectUserRepository->hasRole($projectId, $user->id, [ProjectRoleEnum::ADMIN]);
    }
}
