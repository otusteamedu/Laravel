<?php

namespace App\Services\Queries\FetchAllUsers;

use App\Repositories\Users\UserRepositoryInterface;
use App\Services\DTO\Users\UserDTO;
use App\Services\DTO\Users\PaginatedResult;

class Fetcher
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function fetch(Query $query): PaginatedResult
    {
        $users = $this->userRepository->fetchPaginated($query->limit, $query->offset);
        $total = $this->userRepository->count();

        $userDTOs = array_map(function ($user) {
            return new UserDTO(
                id: $user->id,
                name: $user->name,
                email: $user->email,
                isAdmin: $user->is_admin,
                createdAt: $user->created_at,
                updatedAt: $user->updated_at,
                emailVerifiedAt: $user->email_verified_at,
            );
        }, $users);

        return new PaginatedResult(
            items: $userDTOs,
            total: $total,
            limit: $query->limit,
            offset: $query->offset
        );
    }
} 