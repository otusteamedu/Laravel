<?php

namespace App\Policies;

use App\Models\User;
use App\Domain\Repositories\Project\ValueObject\ProjectRoleEnum;
use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;

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

    public function invited(User $user, int $projectId): bool
    {
        return $this->repository->userInvited($projectId, $user->id);
    }

    public function join(User $user, int $projectId, int $userId): bool
    {
        if ($this->repository->userHasRole($projectId, $userId, [ProjectRoleEnum::ADMIN, ProjectRoleEnum::MEMBER])) {
            return false;
        }

        if ($userId === $user->id) {
            return $this->repository->userInvited($projectId, $user->id);
        }

        if ($this->repository->userHasRole($projectId, $user->id, [ProjectRoleEnum::ADMIN])) {
            return $this->repository->userInvited($projectId, $userId);
        }

        return false;
    }

    public function left(User $user, int $projectId, int $userId): bool
    {
        if (!$this->repository->findUser($projectId, $userId)) {
            return false;
        }

        if ($this->repository->userHasRole($projectId, $userId, [ProjectRoleEnum::ADMIN]) && $user->id === $userId) {
            return false;
        }

        if ($this->repository->userHasRole($projectId, $user->id, [ProjectRoleEnum::ADMIN])  && $user->id !== $userId) {
            return true;
        }

        if ($userId === $user->id) {
            return true;
        }

        return false;
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
