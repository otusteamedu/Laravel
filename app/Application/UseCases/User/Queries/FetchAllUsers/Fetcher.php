<?php

namespace App\Application\UseCases\User\Queries\FetchAllUsers;

use App\Application\UseCases\User\DTO\UserDTO;
use App\Application\UseCases\User\DTO\ResultDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User;

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
     *
     * @return ResultDTO
     */
    public function fetch(): ResultDTO
    {
        $users = $this->userRepository->fetchAll();

        $userDTOs = array_map(function (User $user) {
            return new UserDTO(
                id: $user->getId(),
                name: $user->getName(),
                email: $user->getEmail(),
                createdAt: $user->getCreatedAt(),
                updatedAt: $user->getUpdatedAt(),
                emailVerifiedAt: $user->getEmailVerifiedAt(),
                roles: method_exists($user, 'getRoles') ? $user->getRoles()->roles : [],
                // permissions: method_exists($user, 'getPermissions') ? $user->getPermissions()->permissions : [],
                subscribedNews: $user->getSubscribedNews(),
            );
        }, $users);

        return new ResultDTO($userDTOs);
    }
}
