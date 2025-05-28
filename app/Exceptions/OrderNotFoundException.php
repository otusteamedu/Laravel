<?php

namespace App\Exceptions;

class OrderNotFoundException extends \RuntimeException
{

    public function __construct(
        string $message = 'Order not found',
        int $code = 404,
        \Throwable $previous = null,
    ) 
    {
        parent::__construct($message, $code, $previous);
    }
}