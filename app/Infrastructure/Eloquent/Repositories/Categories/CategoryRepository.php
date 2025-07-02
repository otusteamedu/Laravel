<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\Categories;

use App\Models\Category;
use App\Services\Repositories\CategoryRepositoryInterface;


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
     *
     * @return array
     */
    public function fetchPaginated(int $limit, int $offset): array {
        return Category::query()
                       ->orderBy('id', 'desc')
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
     *
     * @return Category|null
     */
    public function find(int $id): ?Category {
        return Category::query()->find($id);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function existsByName(string $name): bool
    {
        return Category::query()->where('name', $name)->exists();
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
     * @param Category[] $ids
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
     * @return Category[]
     */
    public function getPopular(int $limit): array {
        return Category::query()
                       ->active()
                       ->withCount('publishedNews as news_count')
                       ->orderByDesc('news_count')
                       ->limit($limit)
                       ->get()
                       ->all();
    }
}
