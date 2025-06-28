<?php

declare(strict_types=1);

namespace App\Services\User\Handlers;
use App\Services\User\Repositories\UserRepositoryInterface;
use App\Services\User\Results\Fetcher;

class GetSubscribedNewsHandler
{
    public function __construct(private UserRepositoryInterface $userRepository, private Fetcher $fetcher)
    {
    }

    public function __invoke()
    {
        $subscribedNews = $this->userRepository->findSubscribedNews();

        return $this->fetcher->fetch($subscribedNews);
    }
}
