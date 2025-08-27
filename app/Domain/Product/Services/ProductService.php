<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\Model\Product;
use App\Domain\Product\Repositories\ProductRepositoryInterface;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function createProduct(
        string $title,
        float $price,
        int $userId,
        ?string $alias,
        ?string $text,
        ?string $image,
        ?array $images,
        ?bool $isSale,
        ?bool $published,
        ?int $order,
        ?array $categoryIds = []
    ): Product {
        if ($alias === null) {
            $alias = $this->generateAliasFromTitle($title);
        }

        $product = new Product(
            null,
            $title,
            $alias,
            $text,
            $image,
            $images,
            $isSale,
            $published,
            $order,
            $price,
            $userId,
            $categoryIds
        );

        if ($this->productRepository->existsWithAlias($alias)) {
            throw new \DomainException("Product with alias '{$alias}' already exists");
        }


        return $this->productRepository->save($product);
    }

    private function generateAliasFromTitle(string $title): string
    {
        $alias = strtolower($title);
        $alias = preg_replace('/[^a-z0-9]+/', '-', $alias);
        $alias = trim($alias, '-');
        return $alias;
    }


    public function updateProduct(
        int $productId,
        string $title,
        ?string $alias = null,
        ?string $text = null,
        ?string $image = null,
        ?array $images = null,
        ?bool $isSale = null,
        ?bool $published = null,
        ?int $order = null,
        ?float $price = null,
        ?int $userId = null,
        ?array $categoryIds = null
    ): Product {

        $product = $this->productRepository->findById($productId);

        $originalAlias = $product->getAlias();

        $product = new Product(
            $productId,
            $title,
            $alias,
            $text,
            $image,
            $images,
            $isSale,
            $published,
            $order,
            $price,
            $userId,
            $categoryIds
        );

        $newAlias = $product->getAlias();
        if ($newAlias && $newAlias !== $originalAlias && $this->productRepository->existsWithAlias($newAlias, $product->getId())) {
            throw new \DomainException("Product with alias '{$newAlias}' already exists");
        }


        return $this->productRepository->save($product);
    }

    public function deleteProduct(int $productId): void
    {
        $this->productRepository->delete($productId);
    }

    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->findById($id);
    }

    public function getProductByAlias(string $alias): ?Product
    {
        return $this->productRepository->findByAlias($alias);
    }

    /**
     * @return Product[]
     */
    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }

    /**
     * @return Product[]
     */
    public function getProductsPaginated(int $page = 1, int $perPage = 15): array
    {
        return $this->productRepository->paginate($page, $perPage);
    }

    /**
     * @param array $criteria
     * @return Product[]
     */
    public function searchProducts(array $criteria): array
    {
        return $this->productRepository->findByCriteria($criteria);
    }

}
