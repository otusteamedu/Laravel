<?php

namespace App\Application\UseCases\User\Queries\FetchUserById;

use App\Application\UseCases\User\DTO\UserDTO;
use App\Domain\User\Exceptions\UserNotFoundException;
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
     * @return UserDTO
     * @throws UserNotFoundException
     */
    public function fetch(Query $query): UserDTO
    {
        /** @var ?User $user */
        $user = $this->userRepository->find($query->id);

        if (!$user) {
            throw new UserNotFoundException('Пользователь не найден');
        }

        return new UserDTO(
            id: $user->getId(),
            name: $user->getName(),
            email: $user->getEmail(),
            createdAt: $user->getCreatedAt(),
            updatedAt: $user->getUpdatedAt(),
            emailVerifiedAt: $user->getEmailVerifiedAt(),
            subscribedNews: $user->getSubscribedNews(),
        );
    }
}

