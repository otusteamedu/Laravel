<?php

namespace App\Services\User;

use App\Repositories\UserRoleRepository;
use Throwable;

class ChangeUserRoleService
{
    private string $failureMessage = 'failed ';

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserRoleRepository      $userRoleRepository,
    )
    {
    }

    public function handle(string $email, int $roleId): string
    {

        try {
            if (!$email) {
                return $this->failureMessage . 'empty email';
            }

            if (!in_array($roleId, [1, 2])) {
                return $this->failureMessage . 'invalid roleId';
            }

            $user = $this->userRepository->oneByEmail($email);
            if (is_null($user)) {
                return $this->failureMessage . 'invalid email';
            }

            $user->role_id = $roleId;
            $this->userRepository->update($user);
            $roleName = $this->userRoleRepository->one($roleId)->name;
        } catch (Throwable $e)
        {
            return $this->failureMessage . $e->getMessage();
        }

        return $roleName;
    }

}
