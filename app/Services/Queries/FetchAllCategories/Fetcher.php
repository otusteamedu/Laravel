<?php

namespace App\Services\Queries\FetchAllCategories;

use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Services\DTO\Categories\CategoryDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class Fetcher
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function fetch(Query $query): LengthAwarePaginator
    {
        $paginatedCategories = $this->categoryRepository->getAllPaginated($query->perPage);
        $categories = $paginatedCategories->items();

        $categoryDTOs = array_map(function ($category) {
            return new CategoryDTO(
                id: $category->id,
                name: $category->name,
                color: $category->color,
                description: $category->description,
                tasks_count: $category->tasks()->count(),
            );
        }, $categories);

        $paginator = new LengthAwarePaginator(
            $categoryDTOs,
            $paginatedCategories->total(),
            $paginatedCategories->perPage(),
            $paginatedCategories->currentPage(),
            ['path' => $paginatedCategories->path()]
        );

        return $paginator->withQueryString();
    }
} 