<?php
namespace App\Services\Tasks\Handlers;

use App\Services\Tasks\Results\Fetcher;
use App\Repositories\Tasks\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexHandler
{
    public function __construct(private TaskRepositoryInterface $taskRepository, private Fetcher $fetcher)
    {
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function __invoke(int $perPage = 10): LengthAwarePaginator
    {
        $paginatedTasks = $this->taskRepository->getAllPaginated($perPage);
        return $this->fetcher->fetch($paginatedTasks);
    }
} 