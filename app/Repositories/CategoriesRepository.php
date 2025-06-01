<?php

namespace App\Repositories;

use App\Dto\Category\StoreDto;
use App\Dto\Category\UpdateDto;
use App\Exceptions\CategoryNotFoundException;
use App\Models\Category;

class CategoriesRepository
{
    public function fetchAll(string $sort, string $direction): \Illuminate\Database\Eloquent\Collection
    {
        return Category::orderBy($sort, $direction)->get();
    }

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