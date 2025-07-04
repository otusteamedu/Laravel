<?php

namespace App\Exceptions;

class PaymentNotFoundException extends \RuntimeException
{

    public function __construct(
        string $message = 'Payment not found',
        int $code = 404,
        \Throwable $previous = null,
    ) 
    {
        parent::__construct($message, $code, $previous);
    }
}