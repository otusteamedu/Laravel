<?php

namespace App\Services\Queries\FetchUserById;

use App\Repositories\Users\UserRepositoryInterface;
use App\Services\Exceptions\Users\UserNotFoundException;
use App\Services\DTO\Users\UserDTO;

class Fetcher
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function fetch(Query $query): UserDTO
    {
        $user = $this->userRepository->find($query->id);

        if (!$user) {
            throw new UserNotFoundException('Пользователь не найден');
        }

        return new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            isAdmin: $user->is_admin,
            createdAt: $user->created_at,
            updatedAt: $user->updated_at,
            emailVerifiedAt: $user->email_verified_at,
        );
    }
} 