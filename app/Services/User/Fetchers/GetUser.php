<?php

declare(strict_types=1);

namespace App\Services\User\Fetchers;

use App\Models\User;
use App\Services\User\Exceptions\UserNotFoundException;
use App\Services\User\Repositories\UserRepositoryInterface;
use App\Services\User\Results\UserDTO;

class GetUser
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @param int $userId
     *
     * @return UserDTO
     * @throws UserNotFoundException
     */
    public function fetch(int $userId): UserDTO
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId);

        if (!is_null($user)) {
            return new UserDTO(
                id: $user->id,
                name: $user->name,
                email: $user->email,
                subscribedNews: $user->subscribed_news
            );
        } else {
            throw new UserNotFoundException();
        }
    }
}
