<?php

namespace App\Application\Services;

use App\Application\DTO\ProductData;
use App\Domain\Product\Model\Product;
use App\Domain\Product\Services\ProductService;

class ProductAppService
{
    public function __construct(
        private ProductService $productService
    ) {}

    /**
     * Create a new product
     */
    public function createProduct(
        string $title,
        float $price,
        int $userId,
        ?string $alias = '',
        ?string $text = '',
        ?string $image = '',
        ?array $images = [],
        ?bool $isSale = false,
        ?bool $published = true,
        ?int $order = 0,
        ?array $categoryIds = []
    ): Product {
        return $this->productService->createProduct(
            $title,
            $price,
            $userId,
            $alias,
            $text,
            $image,
            $images,
            $isSale,
            $published,
            $order,
            $categoryIds
        );
    }

    /**
     * Update an existing product
     */
    public function updateProduct(
        int $productId,
        ?string $title = null,
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
        return $this->productService->updateProduct(
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
    }

    /**
     * Delete a product
     */
    public function deleteProduct($productId): void
    {
        $this->productService->deleteProduct($productId);
    }


    /**
     * Get product by ID
     */
    public function getProductById(int $id, bool $withCategories = false): ?Product
    {
        $product = $this->productService->getProductById($id);

        return $product;
    }

    /**
     * Get paginated products
     */
    public function getProductsPaginated(int $page = 1, int $perPage = 15, array $criteria = []): array
    {
        $result = $this->productService->getProductsPaginated($page, $perPage, $criteria);

        /*
        if (!empty($criteria['with_categories'])) {
            foreach ($result['data'] as $product) {
                $categories = $this->productService->getProductCategories($product);
                $product->setCategories($categories);
            }
        }*/

        return $result;
    }

    /**
     * Search products by criteria
     *
     * @return Product[]
     */
    public function searchProducts(array $criteria): array
    {
        return $this->productService->searchProducts($criteria);
    }


}
