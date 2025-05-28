<?php

namespace App\Exceptions;

class ProductNotFoundException extends \RuntimeException
{

    public function __construct(
        string $message = 'Product not found',
        int $code = 404,
        \Throwable $previous = null,
    ) 
    {
        parent::__construct($message, $code, $previous);
    }
}