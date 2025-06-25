<?php

declare(strict_types=1);

namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\Category\Repositories\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @return array
     */
    public function fetchAll(): array {
        return Category::all()->all();
    }

    /**
     * @param string $column
     * @param        $direction
     * @param int    $perPage
     *
     * @return LengthAwarePaginator
     */
    /*public function fetchAllPaginate(string $column = 'id', $direction = 'asc', int $perPage = 10): LengthAwarePaginator {
        return Category::query()->orderBy($column, $direction)->paginate($perPage);
    }*/

    /**
     * @param int $id
     *
     * @return Category|null
     */
    public function find(int $id): ?Category {
        return Category::query()->find($id);
    }

    /**
     * @return Category
     */
    public function create(): Category {
        return new Category;
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
        return Category::query()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    public function findByIds(array $ids): array
    {
        return Category::query()->whereIn('id', $ids)->get()->keyBy('id')->all();
    }


    /**
     * Упростим задачу. Определяем популярность категории по количеству новостей
     *
     * @param int $limit
     *
     * @return array
     */
    public function getPopular(int $limit = 10): array {
        return Category::query()
                       ->withCount('publishedNews as news_count')
                       ->orderByDesc('news_count')
                       ->limit($limit)
                       ->get()
                       ->all();
    }
}
