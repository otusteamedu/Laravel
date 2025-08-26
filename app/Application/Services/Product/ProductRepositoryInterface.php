<?php

namespace App\Application\Services\Product;

use App\Domain\BusinessModels\Product;
use App\Domain\ValueObjects\Lang;

interface ProductRepositoryInterface
{
    /**
     * @return array <int, Product>
     */
    // public function getAll(): array;

    public function store(Product $model): void;

    public function findById(int $id): Product;

    public function findByName(string $name, Lang $lang): Product;

    // public function update(Product $category, ?string $lang = null): void;

    // public function delete(int $id): void;

    /**
     * @return array <int, mixed $value>
     */
    public function getValueByField(string $field): array;
}
