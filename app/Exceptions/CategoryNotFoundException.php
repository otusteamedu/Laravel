<?php

namespace App\Exceptions;

class CategoryNotFoundException extends \RuntimeException
{

    public function __construct(
        string $message = 'Category not found',
        int $code = 404,
        \Throwable $previous = null,
    ) 
    {
        parent::__construct($message, $code, $previous);
    }
}