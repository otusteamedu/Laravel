<?php

namespace App\Application\UseCases\User\Queries\FetchAllUsersPagination;

use App\Application\UseCases\User\DTO\PaginatedResult;
use App\Application\UseCases\User\DTO\UserDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User;

class Fetcher
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @param Query $query
     * @return PaginatedResult
     */
    public function fetch(Query $query): PaginatedResult
    {
        $users = $this->userRepository->fetchPaginated($query->limit, $query->offset);
        $total = $this->userRepository->count();

        if (!is_array($users)) {
            $users = iterator_to_array($users);
        }

        $userDTOs = array_map(function (User $user) {
            return new UserDTO(
                id: $user->getId(),
                name: $user->getName(),
                email: $user->getEmail(),
                createdAt: $user->getCreatedAt(),
                updatedAt: $user->getUpdatedAt(),
                emailVerifiedAt: $user->getEmailVerifiedAt(),
                subscribedNews: $user->getSubscribedNews()
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

