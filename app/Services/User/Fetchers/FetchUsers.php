<?php

declare(strict_types=1);

namespace App\Services\User\Fetchers;

use App\Models\User;
use App\Services\User\Repositories\UserRepositoryInterface;
use App\Services\User\Results\UserDTO;
use App\Services\User\Results\UsersDTO;

class FetchUsers
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @return UsersDTO
     */
    public function fetch(): UsersDTO
    {
        /** @var User $user */
        $users = $this->userRepository->fetchAll();

        $userDTOs = array_map(fn(User $user) => new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            subscribedNews: $user->subscribed_news
        ), $users);

        return new UsersDTO($userDTOs);
    }
}
