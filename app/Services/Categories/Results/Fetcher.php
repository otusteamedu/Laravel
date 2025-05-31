<?php
namespace App\Services\Categories\Results;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class Fetcher
{
    /**
     * Преобразует пагинированную коллекцию категорий в пагинированную коллекцию DTO
     *
     * @param LengthAwarePaginator $paginatedCategories
     * @return LengthAwarePaginator
     */
    public function fetch(LengthAwarePaginator $paginatedCategories): LengthAwarePaginator
    {
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
