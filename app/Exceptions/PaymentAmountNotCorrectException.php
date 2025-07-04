<?php

namespace App\Exceptions;

class PaymentAmountNotCorrectException extends \RuntimeException
{

    public function __construct(
        string $message = 'Payment amount not correct',
        int $code = 400,
        \Throwable $previous = null,
    ) 
    {
        parent::__construct($message, $code, $previous);
    }
}