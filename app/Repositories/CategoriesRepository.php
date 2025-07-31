<?php

namespace App\Repositories;

use App\Dto\Admin\Category\StoreDto;
use App\Dto\Admin\Category\UpdateDto;
use App\Exceptions\CategoryNotFoundException;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoriesRepository
{
    /**
     * @return Collection<array-key, Category>
     */
    public function fetchAll(bool $warmup = false): Collection
    {
        $key = 'categories.all';
        $ttl = now()->addWeek();
        $tag = 'category-list';

        if ($warmup) {
            $categories = Category::all();
            Cache::tags($tag)->put($key, $categories, $ttl);
            return $categories;
        }

        $categories = Cache::tags($tag)->remember($key, $ttl, function () {
            return Category::all();
        });
        
        return $categories;
    }

    /**
     * @return Collection<array-key, Category>
     */
    public function fetchAllWithSort(string $sort, string $direction, $warmup = false): Collection
    {
        $key = 'categories.' . $sort . '.' . $direction;
        $ttl = now()->addWeek();
        $tag = 'category-list';

        if ($warmup) {
            $categories = Category::orderBy($sort, $direction)->get();
            Cache::tags($tag)->put($key, $categories, $ttl);
            return $categories;
        }

        $categories = Cache::tags($tag)->remember($key, $ttl, function () use ($sort, $direction) {
            return Category::orderBy($sort, $direction)->get();
        });
        
        return $categories;
    }

    /**
     * @return Category
     */
    public function find(int $categoryId, bool $warmup = false): Category
    {
        $key = 'category.' . $categoryId;
        $ttl = now()->addWeek();

        if ($warmup) {
            $category = Category::find($categoryId);
            Cache::put($key, $category, $ttl);
            return $category;
        }

        $category = Cache::remember($key, $ttl, function () use ($categoryId) {
            return Category::find($categoryId);
        });

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        return $category;
    }

    /**
     * @return int
     */
    public function count(bool $warmup = false): int
    {
        
        $key = 'category.count';
        $ttl = now()->addWeek();
        
        if ($warmup) {
            $count = Category::count();
            Cache::put($key, $count, $ttl);
            return $count;
        }
        
        $count = Cache::remember($key, $ttl, function () {
            return Category::count();
        });

        return $count;
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

        Cache::forget('category.' . $updateDto->id);
        Cache::tags('category-list')->flush();
    }

    public function add(StoreDto $storeDto): void
    {
        $category = new Category();
        $category->title = $storeDto->title;
        $category->description = $storeDto->description;
        $category->save();

        Cache::tags('category-list')->flush();
        Cache::forget('category.count');
    }

    public function delete(int $categoryId): void
    {
        $category = Category::find($categoryId);

        if (!$category) {
            throw new CategoryNotFoundException();
        }
        
        $category->delete();

        Cache::forget('category.' . $categoryId);
        Cache::tags('category-list')->flush();
        Cache::forget('category.count');
    }

    public function warmupCache(): void
    {
        $categories = $this->fetchAll(true);

        $sorts = config('custom.categoriesSorts');
        $directions = config('custom.categoriesDirections');
        foreach ($sorts as $sort) {
            foreach ($directions as $direction) {
                $this->fetchAllWithSort($sort, $direction, true);
            }
        }
        
        $ids = $categories->pluck('id')->toArray();
        foreach ($ids as $categoryId) {
            $this->find($categoryId, true);
        }

        $this->count(true);
    }
}