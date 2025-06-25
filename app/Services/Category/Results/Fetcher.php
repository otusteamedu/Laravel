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
    public function fetch(array|Category $categories): CategoriesDTO|CategoryDTO
    {
        if (is_array($categories)) {
            $categoryDTOs = array_map(fn (Category $category) => $this->wrapItem($category), $categories);

            return new CategoriesDTO($categoryDTOs);
        } else {
            return $this->wrapItem($categories);
        }
    }

    /**
     * @param Category $category
     *
     * @return CategoryDTO
     */
    private function wrapItem(Category $category): CategoryDTO {
        return new CategoryDTO(
            id: $category->id,
            name: $category->name,
            slug: $category->slug,
            sort: $category->sort,
            newsCount: $category->news_count ?? null,
        );
    }
}
