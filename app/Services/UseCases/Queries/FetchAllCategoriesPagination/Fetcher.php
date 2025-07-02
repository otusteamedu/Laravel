<?php

namespace App\Services\UseCases\Queries\FetchAllCategoriesPagination;

use App\Models\Category;
use App\Services\DTO\Categories\CategoryDTO;
use App\Services\DTO\Categories\PaginatedResult;
use App\Services\Repositories\CategoryRepositoryInterface;

class Fetcher
{
    /**
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    /**
     * @param Query $query
     *
     * @return PaginatedResult
     */
    public function fetch(Query $query): PaginatedResult
    {
        $categories = $this->categoryRepository->fetchPaginated($query->limit, $query->offset);
        $total = $this->categoryRepository->count();

        $categoryDTOs = array_map(function (Category $category) {
            return new CategoryDTO(
                id: $category->id,
                name: $category->name,
                slug: $category->slug,
                isActive: $category->is_active,
                sort: $category->sort,
                newsCount: null,
            );
        }, $categories);

        return new PaginatedResult(
            items: $categoryDTOs,
            total: $total,
            limit: $query->limit,
            offset: $query->offset
        );
    }
}
