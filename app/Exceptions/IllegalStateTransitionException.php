<?php

namespace App\Exceptions;

class IllegalStateTransitionException extends \RuntimeException
{

    public function __construct(
        string $message = 'Illegal state transition',
        int $code = 400,
        \Throwable $previous = null,
    ) 
    {
        parent::__construct($message, $code, $previous);
    }
}