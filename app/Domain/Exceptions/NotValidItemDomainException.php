<?php

namespace App\Domain\Exceptions;

use Exception;

class NotValidItemDomainException extends Exception
{
    protected $message = 'Не валидное значение.';
    protected $code = 422;

    public function __construct($message = null, $code = null)
    {
        parent::__construct($message ?? $this->message, $code ?? $this->code);
    }
}
