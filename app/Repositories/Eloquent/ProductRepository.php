<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    protected Product $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getAllPaginated(int $perPage = 10, string $orderBy = 'order', int $page = null): LengthAwarePaginator
    {
        $query = $this->model->orderBy($orderBy);

        if ($page) {
            return $query->paginate($perPage, ['*'], 'page', $page);
        }

        return $query->paginate($perPage);
    }

    public function find(int $id): ?Product
    {
        return $this->model->find($id);
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function syncCategories(Product $product, array $categoryIds): void
    {
        $product->categories()->sync($categoryIds);
    }

    public function search(string $query, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('title', 'like', '%' . $query . '%')
            ->orWhere('text', 'like', '%' . $query . '%')
            ->paginate($perPage);
    }
}
