<?php

namespace App\Application\UseCases\Category\Queries\FetchAllCategoriesPagination;

use App\Application\UseCases\Category\DTO\CategoryDTO;
use App\Application\UseCases\Category\DTO\PaginatedResult;
use App\Domain\News\Repositories\CategoryRepositoryInterface;
use App\Domain\News\Entities\Category;

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
                id: $category->getId(),
                name: $category->getName(),
                slug: $category->getSlug(),
                isActive: $category->getIsActive(),
                sort: $category->getSort(),
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
