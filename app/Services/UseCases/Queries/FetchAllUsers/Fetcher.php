<?php

namespace App\Services\UseCases\Queries\FetchAllUsers;

use App\Models\User;
use App\Services\DTO\Users\UserDTO;
use App\Services\DTO\Users\UsersDTO;
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
     *
     * @return UsersDTO
     */
    public function fetch(): UsersDTO
    {
        $users = $this->userRepository->fetchAll();

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

        return new UsersDTO($userDTOs);
    }
}
