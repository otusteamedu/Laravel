<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProjectRoleEnum;
use App\Services\Repositories\ProjectRepositoryInterface;

class ProjectPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct(
        private ProjectRepositoryInterface $repository,
    ) {
        //
    }

    public function create(User $user)
    {
        return !empty($user);
    }

    public function view(User $user, int $projectId): bool
    {
        return $this->repository->userHasRole($projectId, $user->id, [ProjectRoleEnum::ADMIN, ProjectRoleEnum::MEMBER]);
    }

    public function update(User $user, int $projectId): bool
    {
        return $this->repository->userHasRole($projectId, $user->id, [ProjectRoleEnum::ADMIN]);
    }

    public function delete(User $user, int $projectId): bool
    {
        return $this->repository->userHasRole($projectId, $user->id, [ProjectRoleEnum::ADMIN]);
    }

    public function user_list(User $user, int $projectId): bool
    {
        return $this->repository->userHasRole($projectId, $user->id, [ProjectRoleEnum::ADMIN, ProjectRoleEnum::MEMBER]);
    }

    public function user_manage(User $user, int $projectId): bool
    {
        return $this->repository->userHasRole($projectId, $user->id, [ProjectRoleEnum::ADMIN]);
    }
}
