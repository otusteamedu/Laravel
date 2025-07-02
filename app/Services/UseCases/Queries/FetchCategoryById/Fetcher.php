<?php

namespace App\Services\UseCases\Queries\FetchCategoryById;

use App\Models\Category;
use App\Services\DTO\Categories\CategoryDTO;
use App\Services\Exceptions\Categories\CategoryNotFoundException;
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
     * @return CategoryDTO
     * @throws CategoryNotFoundException
     */
    public function fetch(Query $query): CategoryDTO
    {
        /** @var ?Category $category */
        $category = $this->categoryRepository->find($query->id);

        if (!$category) {
            throw new CategoryNotFoundException('Категория не найдена');
        }

        return new CategoryDTO(
            id: $category->id,
            name: $category->name,
            slug: $category->slug,
            isActive: $category->is_active,
            sort: $category->sort,
            newsCount: null,
        );
    }
}
