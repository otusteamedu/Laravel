<?php

namespace App\Domain\Factories\Product;

use App\Domain\BusinessModels\Product;
use App\Domain\ValueObjects\Product\ProductDescription;
use App\Domain\ValueObjects\Product\ProductName;
use App\Domain\ValueObjects\Lang;

class ProductFactory
{
    public static function make(string $name, string $description, string $lang): Product
    {
        $productName = new ProductName($name);
        $productDescription = new ProductDescription($description);
        $lang = new Lang($lang);

        return new Product($productName, $productDescription, $lang);
    }
}
