<?php

namespace App\Services\Exceptions\Categories;

use Exception;

final class CategoryAlreadyExistsException extends Exception
{
    public function __construct(string $categoryName)
    {
        parent::__construct("Категория с именем '{$categoryName}' уже существует");
    }
}
