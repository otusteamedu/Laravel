<?php
namespace App\Services\Categories\Handlers;

use App\Services\Categories\Results\Fetcher;
use App\Repositories\Categories\CategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexHandler
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository, private Fetcher $fetcher)
    {
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function __invoke(int $perPage = 10): LengthAwarePaginator
    {
        $paginatedCategories = $this->categoryRepository->getAllPaginated($perPage);
        return $this->fetcher->fetch($paginatedCategories);
    }
}
