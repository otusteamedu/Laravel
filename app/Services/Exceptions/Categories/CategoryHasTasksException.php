<?php
namespace App\Services\Exceptions\Categories;

use Exception;

class CategoryHasTasksException extends Exception
{
    public function __construct(string $categoryName)
    {
        parent::__construct("Нельзя удалить категорию '{$categoryName}', в ней есть задачи");
    }
}
