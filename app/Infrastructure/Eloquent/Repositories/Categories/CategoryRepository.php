<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\Categories;

use App\Domain\News\Repositories\CategoryRepositoryInterface;
use App\Domain\News\Entities\Category as DomainCategory;
use App\Models\Category as EloquentCategory;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @return DomainCategory[]
     */
    public function fetchAll(): array
    {
        $models = EloquentCategory::all();
        return array_map([CategoryMapper::class, 'toEntity'], $models->all());
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return DomainCategory[]
     */
    public function fetchPaginated(int $limit, int $offset): array
    {
        $models = EloquentCategory::orderBy('id', 'desc')
                                  ->limit($limit)
                                  ->offset($offset)
                                  ->get();

        return array_map([CategoryMapper::class, 'toEntity'], $models->all());
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return EloquentCategory::count();
    }

    /**
     * @param int $id
     * @return DomainCategory|null
     */
    public function find(int $id): ?DomainCategory
    {
        $model = EloquentCategory::find($id);
        return $model ? CategoryMapper::toEntity($model) : null;
    }

    public function existsBySlug(string $slug): bool
    {
        return EloquentCategory::where('slug', $slug)->exists();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function existsByName(string $name): bool
    {
        return EloquentCategory::where('name', $name)->exists();
    }

    /**
     * @param DomainCategory $category
     * @return DomainCategory
     */
    public function save(DomainCategory $category): DomainCategory
    {
        $model = CategoryMapper::toModel($category);

        $model->save();

        return CategoryMapper::toEntity($model);
    }

    /**
     * @param DomainCategory $category
     * @return bool|null
     */
    public function delete(DomainCategory $category): ?bool
    {
        $model = EloquentCategory::find($category->getId());
        return $model ? $model->delete() : null;
    }

    /**
     * @param string $slug
     * @return DomainCategory|null
     */
    public function findBySlug(string $slug): ?DomainCategory
    {
        $model = EloquentCategory::where('slug', $slug)->first();
        return $model ? CategoryMapper::toEntity($model) : null;
    }

    /**
     * @param int[] $ids
     * @return DomainCategory[]
     */
    public function findByIds(array $ids): array
    {
        $models = EloquentCategory::whereIn('id', $ids)->get();
        return array_map([CategoryMapper::class, 'toEntity'], $models->all());
    }

    /**
     * Определяем популярность категории по количеству новостей
     * @param int $limit
     * @return EloquentCategory[]
     */
    public function getPopular(int $limit): array
    {
        $models = EloquentCategory::active()
                                  ->withCount('publishedNews as news_count')
                                  ->orderByDesc('news_count')
                                  ->limit($limit)
                                  ->get();

        return $models->all();
    }
}
