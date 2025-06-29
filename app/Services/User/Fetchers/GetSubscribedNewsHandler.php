<?php

declare(strict_types=1);

namespace App\Services\User\Fetchers;
use App\Models\User;
use App\Services\User\Repositories\UserRepositoryInterface;
use App\Services\User\Results\UserDTO;
use App\Services\User\Results\UsersDTO;

class GetSubscribedNewsHandler
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @return UsersDTO
     */
    public function __invoke(): UsersDTO
    {
        $subscribedNews = $this->userRepository->findSubscribedNews();

        $userDTOs = array_map(fn(User $user) => new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            subscribedNews: $user->subscribed_news
        ), $subscribedNews);

        return new UsersDTO($userDTOs);
    }
}
