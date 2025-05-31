<?php
namespace App\Services\Users\Handlers;

use App\Services\Users\Results\Fetcher;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexHandler
{
    public function __construct(private UserRepositoryInterface $userRepository, private Fetcher $fetcher)
    {
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function __invoke(int $perPage = 10): LengthAwarePaginator
    {
        $paginatedUsers = $this->userRepository->getAllPaginated($perPage);
        return $this->fetcher->fetch($paginatedUsers);
    }
}
