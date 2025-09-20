<?php

namespace App\Domain\Factories\Category;

use App\Domain\BusinessModels\Category;
use App\Domain\ValueObjects\Category\CategoryDescription;
use App\Domain\ValueObjects\Category\CategoryName;
use App\Domain\ValueObjects\Lang;

class CategoryFactory
{
    public static function make(string $name, string $description, string $lang, string $apiId): Category
    {
        $categoryName = new CategoryName($name);
        $categoryDescription = new CategoryDescription($description);
        $lang = new Lang($lang);

        return new Category($categoryName, $categoryDescription, $lang, $apiId);
    }
}
