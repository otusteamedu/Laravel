<?php

namespace App\Services\UseCases\Queries\FetchAllCategories;

use App\Models\Category;
use App\Services\DTO\Categories\CategoriesDTO;
use App\Services\DTO\Categories\CategoryDTO;
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
     *
     * @return CategoriesDTO
     */
    public function fetch(): CategoriesDTO
    {
        $categories = $this->categoryRepository->fetchAll();

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

        return new CategoriesDTO($categoryDTOs);
    }
}
