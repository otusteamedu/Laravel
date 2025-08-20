<?php

namespace App\Domain\Exceptions;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class DomainException extends Exception implements NotFoundExceptionInterface 
{
    protected $message = 'Ошибка отловлена в Domain слое.';
    protected $code = 500;

    public function __construct($message = null, $code = null, \Throwable|null $previos = null)
    {
        parent::__construct($message ?? $this->message, $code ?? $this->code, $previos);
    }
}