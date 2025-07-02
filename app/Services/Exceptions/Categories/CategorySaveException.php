<?php

namespace App\Services\Exceptions\Categories;

use Exception;

final class CategorySaveException extends Exception
{
    public function __construct(string $message = "Не удалось сохранить категорию")
    {
        parent::__construct($message);
    }
}
