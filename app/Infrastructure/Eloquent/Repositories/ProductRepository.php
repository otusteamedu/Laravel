<?php

namespace App\Infrastructure\Eloquent\Repositories;
use App\Domain\Product\Model\Product;
use App\Infrastructure\Eloquent\Models\Product as EloquentProduct;
use App\Domain\Product\Repositories\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function findById(int $id): ?Product
    {
        $model = EloquentProduct::with('categories')->find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByAlias(string $alias): ?Product
    {
        $model = EloquentProduct::with('categories')
            ->where('alias', $alias)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function findAll(): array
    {
        return EloquentProduct::with('categories')
            ->orderBy('order')
            ->get()
            ->map(fn($model) => $this->toEntity($model))
            ->toArray();
    }

    public function findByCriteria(array $criteria): array
    {
        $query = EloquentProduct::with('categories');

        if (isset($criteria['published'])) {
            $query->where('published', (bool)$criteria['published']);
        }

        if (isset($criteria['is_sale'])) {
            $query->where('is_sale', (bool)$criteria['is_sale']);
        }

        if (isset($criteria['category_id'])) {
            $query->whereHas('categories', function ($q) use ($criteria) {
                $q->where('id', $criteria['category_id']);
            });
        }

        if (isset($criteria['title'])) {
            $query->where('title', 'like', "%{$criteria['title']}%");
        }

        if (isset($criteria['min_price'])) {
            $query->where('price', '>=', (float)$criteria['min_price']);
        }

        if (isset($criteria['max_price'])) {
            $query->where('price', '<=', (float)$criteria['max_price']);
        }

        $sort = $criteria['sort'] ?? 'order';
        $direction = $criteria['direction'] ?? 'asc';

        if (!in_array($sort, ['price', 'order', 'created_at', 'title'])) {
            $sort = 'order';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $query->orderBy($sort, $direction);

        return $query->get()
            ->map(fn($model) => $this->toEntity($model))
            ->toArray();
    }

    public function delete(int $productId): void
    {
        $model = EloquentProduct::find($productId);
        $model->delete();
    }

    public function paginate(int $page = 1, int $perPage = 15): array
    {
        $paginator = EloquentProduct::with('categories')
            ->orderBy('order')
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

    public function save(Product $product): Product
    {

        $data = [
            'title' => $product->getTitle(),
            'alias' => $product->getAlias(),
            'text' => $product->getText(),
            'image' => $product->getImage(),
            'images' => json_encode($product->getImages()),
            'is_sale' => $product->getIsSale(),
            'published' => $product->getPublished(),
            'order' => $product->getOrder(),
            'price' => $product->getPrice(),
            'user_id' => $product->getUserId(),
        ];

        $data = array_filter($data, fn($value) => $value !== null);

        if($product->getId()){
            EloquentProduct::where('id', $product->getId())->update($data);
            $model = EloquentProduct::find($product->getId());
        }else{
            $model = EloquentProduct::create($data);
        }

        if ($model) {
            $categoryIds = $product->getCategoryIds();
            $model->categories()->sync($categoryIds);
        }

        return $this->toEntity($model);

    }

    private function toEntity(EloquentProduct $model): Product
    {
        $images = $model->images ? json_decode($model->images, true) : [];
        if (!is_array($images)) {
            $images = [];
        }

        $product = new Product(
            $model->id,
            $model->title,
            $model->alias,
            $model->text,
            $model->image,
            $images,
            (bool)$model->is_sale,
            (bool)$model->published,
            $model->order,
            (float)$model->price,
            $model->user_id,
            $model->categories->pluck('id')->toArray()
        );

        return $product;
    }

    public function existsWithAlias(string $alias, ?int $excludeId = null): bool
    {
        $query = EloquentProduct::where('alias', $alias);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
