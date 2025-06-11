<?php

namespace App\Repositories;

use App\Dto\Admin\Category\StoreDto;
use App\Dto\Admin\Category\UpdateDto;
use App\Exceptions\CategoryNotFoundException;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoriesRepository
{
    /**
     * @return Collection<array-key, Category>
     */
    public function fetchAll(): Collection
    {
        return Category::all();
    }

    /**
     * @return Collection<array-key, Category>
     */
    public function fetchAllWithSort(string $sort, string $direction): Collection
    {
        return Category::orderBy($sort, $direction)->get();
    }

    /**
     * @return Category
     */
    public function find(int $categoryId): Category
    {
        $category = Category::find($categoryId);

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        return $category;
    }

    public function save(UpdateDto $updateDto): void
    {
        $category = Category::find($updateDto->id);

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        $category->title = $updateDto->title;
        $category->description = $updateDto->description;
        $category->save();
    }

    public function add(StoreDto $storeDto): void
    {
        $category = new Category();
        $category->title = $storeDto->title;
        $category->description = $storeDto->description;
        $category->save();
    }

    public function delete(int $categoryId): void
    {
        $category = Category::find($categoryId);

        if (!$category) {
            throw new CategoryNotFoundException();
        }
        
        $category->delete();
    }
}