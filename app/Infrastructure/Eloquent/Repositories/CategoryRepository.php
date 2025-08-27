<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Domain\Category\Model\Category;
use App\Infrastructure\Eloquent\Models\Category as EloquentCategory;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function findById(int $id): ?Category
    {
        $model = EloquentCategory::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByAlias(string $alias): ?Category
    {
        $model = EloquentCategory::where('alias', $alias)->first();
        return $model ? $this->toEntity($model) : null;
    }

    public function findAll(): array
    {
        return EloquentCategory::orderBy('order')
            ->get()
            ->map(fn($model) => $this->toEntity($model))
            ->toArray();
    }

    public function findByCriteria(array $criteria): array
    {
        $query = EloquentCategory::query();

        if (isset($criteria['published'])) {
            $query->where('published', (bool)$criteria['published']);
        }

        if (isset($criteria['title'])) {
            $query->where('title', 'like', "%{$criteria['title']}%");
        }

        if (isset($criteria['order_by'])) {
            $direction = $criteria['direction'] ?? 'asc';
            $query->orderBy($criteria['order_by'], $direction);
        } else {
            $query->orderBy('order');
        }

        return $query->get()
            ->map(fn($model) => $this->toEntity($model))
            ->toArray();
    }

    public function paginate(int $page = 1, int $perPage = 15): array
    {
        $paginator = EloquentCategory::orderBy('order')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $paginator->getCollection()
                ->map(fn($model) => $this->toEntity($model))
                ->toArray(),
            'total' => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'last_page' => $paginator->lastPage(),
        ];
    }

    public function save(Category $category): Category
    {
        $data = [
            'title' => $category->getTitle(),
            'alias' => $category->getAlias(),
            'text' => $category->getText(),
            'published' => $category->getPublished(),
            'order' => $category->getOrder()
        ];

        if ($category->getId()) {
            EloquentCategory::where('id', $category->getId())->update($data);
            $model = EloquentCategory::find($category->getId());
        } else {
            $model = EloquentCategory::create($data);
        }

        return $this->toEntity($model);
    }

    public function delete(int $categoryId): void
    {
        EloquentCategory::destroy($categoryId);
    }

    public function existsWithAlias(string $alias, ?int $excludeId = null): bool
    {
        $query = EloquentCategory::where('alias', $alias);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function count(): int
    {
        return EloquentCategory::count();
    }



    private function toEntity(EloquentCategory $model): Category
    {
        return new Category(
            $model->id,
            $model->title,
            $model->alias,
            $model->text,
            (bool)$model->published,
            $model->order
        );
    }
}
