<?php
declare(strict_types=1);

namespace App\Services\Category\Results;

use App\Models\Category;

class Fetcher
{
    /**
     * @param Category[] $categories
     *
     * @return CategoriesDTO
     */
    public function fetch(array $categories): CategoriesDTO
    {
        $categoryDTOs = array_map(fn (Category $category) => new CategoryDTO(
            id: $category->id,
            name: $category->name,
            slug: $category->slug,
            sort: $category->sort,
        ), $categories);

        return new CategoriesDTO($categoryDTOs);
    }
}
