<?php

namespace App\Application\UseCases\Category\Queries\FetchAllCategories;

use App\Application\UseCases\Category\DTO\ResultDTO;
use App\Application\UseCases\Category\DTO\CategoryDTO;
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
     *
     * @return ResultDTO
     */
    public function fetch(): ResultDTO
    {
        $categories = $this->categoryRepository->fetchAll();

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

        return new ResultDTO($categoryDTOs);
    }
}
