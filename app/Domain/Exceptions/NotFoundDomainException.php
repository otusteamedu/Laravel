<?php

namespace App\Domain\Exceptions;

use Exception;

class NotFoundDomainException extends Exception
{
    protected $message = 'Запись не найдена.';
    protected $code = 404;

    public function __construct($message = null, $code = null)
    {
        parent::__construct($message ?? $this->message, $code ?? $this->code);
    }
}
