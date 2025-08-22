<?php

namespace App\Domain\Exceptions;

use DomainException as BaseDomainException;

class DomainException extends BaseDomainException
{
    protected $message = 'Ошибка отловлена в Domain слое.';
    protected $code = 500;

    public function __construct($message = null, $code = null, \Throwable|null $previos = null)
    {
        parent::__construct($message ?? $this->message, $code ?? $this->code, $previos);
    }
}