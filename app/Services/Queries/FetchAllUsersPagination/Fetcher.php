<?php

namespace App\Services\Queries\FetchAllUsersPagination;

use App\Models\User;
use App\Services\DTO\Users\PaginatedResult;
use App\Services\DTO\Users\UserDTO;
use App\Services\Repositories\UserRepositoryInterface;

class Fetcher
{
    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @param Query $query
     *
     * @return PaginatedResult
     */
    public function fetch(Query $query): PaginatedResult
    {
        $users = $this->userRepository->fetchPaginated($query->limit, $query->offset);
        $total = $this->userRepository->count();

        $userDTOs = array_map(function (User $user) {
            return new UserDTO(
                id: $user->id,
                name: $user->name,
                email: $user->email,
                createdAt: $user->created_at,
                updatedAt: $user->updated_at,
                emailVerifiedAt: $user->email_verified_at,
                isAdmin: (bool)$user->is_admin,
                subscribedNews: $user->subscribed_news
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
