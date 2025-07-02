<?php

namespace App\Services\Queries\FetchUserById;

use App\Models\User;
use App\Services\DTO\Users\UserDTO;
use App\Services\Exceptions\Users\UserNotFoundException;
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
            id: $user->id,
            name: $user->name,
            email: $user->email,
            createdAt: $user->created_at,
            updatedAt: $user->updated_at,
            emailVerifiedAt: $user->email_verified_at,
            isAdmin: (bool)$user->is_admin,
            subscribedNews: $user->subscribed_news
        );
    }
}
