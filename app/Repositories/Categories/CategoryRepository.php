<?php

namespace App\Repositories\Categories;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @param Category $category
     */
    public function __construct(private Category $category)
    {
    }

    /**
     * @return array
     */
    public function fetchAll(): array {
        return $this->category::all()->all();
    }

    /**
     * @param string $column
     * @param        $direction
     * @param int    $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator {
        return $this->category->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * @param int $id
     *
     * @return Category|null
     */
    public function find(int $id): ?Category {
        return $this->category->find($id);
    }

    /**
     * @return Category
     */
    public function create(): Category {
        return $this->category;
    }

    /**
     * @param Category $category
     *
     * @return bool
     */
    public function save(Category $category): bool {
        return $category->save();
    }

    /**
     * @param Category $category
     *
     * @return bool|null
     */
    public function delete(Category $category): ?bool {
        return $category->delete();
    }
}
