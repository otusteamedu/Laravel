<?php

namespace App\Repositories\Categories;

use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @return Category[]
     */
    public function fetchAll(): array {
        return Category::all()->all();
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Category[]
     */
    public function fetchPaginated(int $limit, int $offset): array
    {
        return Category::orderBy('id', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get()
            ->all();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return Category::count();
    }

    /**
     * @param int $id
     * @return Category|null
     */
    public function find(int $id): ?Category {
        return Category::find($id);
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function save(Category $category): bool {
        return $category->save();
    }

    /**
     * @param Category $category
     * @return bool|null
     */
    public function delete(Category $category): ?bool {
        return $category->delete();
    }
}
