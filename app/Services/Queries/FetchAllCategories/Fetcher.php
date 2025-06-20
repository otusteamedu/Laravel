<?php

namespace App\Services\Queries\FetchAllCategories;

use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Services\DTO\Categories\CategoryDTO;
use App\Services\DTO\Categories\PaginatedResult;

class Fetcher
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function fetch(Query $query): PaginatedResult
    {
        $categories = $this->categoryRepository->fetchPaginated($query->limit, $query->offset);
        $total = $this->categoryRepository->count();

        $categoryDTOs = array_map(function ($category) {
            return new CategoryDTO(
                id: $category->id,
                name: $category->name,
                color: $category->color,
                description: $category->description,
                tasks_count: $category->tasks()->count(),
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