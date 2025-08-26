<?php

namespace App\Domain\News\Exceptions;

use Exception;

final class CategorySaveException extends Exception
{
    public function __construct(string $message = "Не удалось сохранить категорию")
    {
        parent::__construct($message);
    }
}
