<?php

namespace App\Application\Exceptions;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class ServiceException extends Exception implements NotFoundExceptionInterface 
{
    protected $message = 'Ошибка отловлена в Application слое.';
    protected $code = 500;

    public function __construct($message = null, $code = null, \Throwable|null $previos = null)
    {
        parent::__construct($message ?? $this->message, $code ?? $this->code, $previos);
    }
}