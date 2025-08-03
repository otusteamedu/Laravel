<?php

namespace App\DTO;
readonly class ProductPriceData
{
    /**
     * @param int $productId The ID of the product.
     * @param float $oldPrice The old price of the product.
     * @param float $newPrice The new price of the product.
     */
    public function __construct(
        public int $productId,
        public float $oldPrice,
        public float $newPrice
    ) {}
}
