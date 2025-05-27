<?php

declare(strict_types=1);

namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\Category\Repositories\CategoryRepositoryInterface;

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
    /*public function fetchAllPaginate(string $column = 'id', $direction = 'asc', int $perPage = 10): LengthAwarePaginator {
        return $this->category->query()->orderBy($column, $direction)->paginate($perPage);
    }*/

    /**
     * @param int $id
     *
     * @return Category|null
     */
    public function find(int $id): ?Category {
        return $this->category::query()->find($id);
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


    /**
     * @param string $slug
     *
     * @return Category|null
     */
    public function findBySlug(string $slug): ?Category
    {
        return $this->category::query()
            ->where('slug', $slug)
            ->where('active', true)
            ->first();
    }
}
