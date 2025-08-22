<?php

namespace App\Domain\Exceptions;

use DomainException as BaseDomainException;

class NotValidItemDomainException extends BaseDomainException
{
    protected $message = 'Не валидное значение.';
    protected $code = 422;

    public function __construct($message = null, $code = null)
    {
        parent::__construct($message ?? $this->message, $code ?? $this->code);
    }
}
