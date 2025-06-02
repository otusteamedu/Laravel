<?php

namespace App\Exceptions;

class StockIsEmptyException extends \RuntimeException
{

    public function __construct(
        string $message = 'There are not enough items to add to cart',
        int $code = 404,
        \Throwable $previous = null,
    ) 
    {
        parent::__construct($message, $code, $previous);
    }
}