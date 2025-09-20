<?php

namespace App\Domain\Factories\Product;

use App\Domain\BusinessModels\Product;
use App\Domain\ValueObjects\Product\ProductName;
use App\Domain\ValueObjects\Lang;

class ProductFactory
{
    public static function make(string $name, string $lang): Product
    {
        $productName = new ProductName($name);
        $lang = new Lang($lang);

        return new Product($productName, $lang);
    }
}
