<?php

namespace App\Services\Queries\FetchCategoryById;

use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Services\Exceptions\Categories\CategoryNotFoundException;
use App\Services\DTO\Categories\CategoryDTO;

class Fetcher
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function fetch(Query $query): CategoryDTO
    {
        $category = $this->categoryRepository->find($query->id);

        if (!$category) {
            throw new CategoryNotFoundException('Категория не найдена');
        }

        return new CategoryDTO(
            id: $category->id,
            name: $category->name,
            color: $category->color,
            description: $category->description,
            tasks_count: $category->tasks()->count(),
        );
    }
} 