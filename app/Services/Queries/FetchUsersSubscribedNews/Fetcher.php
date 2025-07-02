<?php

namespace App\Services\Queries\FetchUsersSubscribedNews;

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
        /** @var ?User $user */
        $subscribedNewsUsers = $this->userRepository->findSubscribedNews();

        $userDTOs = array_map(fn(User $user) => new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            createdAt: $user->created_at,
            updatedAt: $user->updated_at,
            emailVerifiedAt: $user->email_verified_at,
            isAdmin: (bool)$user->is_admin,
            subscribedNews: $user->subscribed_news
        ), $subscribedNewsUsers);

        return new UsersDTO($userDTOs);
    }
}
