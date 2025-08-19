<?php

namespace App\Domain\Exceptions;

use Exception;

class NotAdminDomainException extends Exception
{
    protected $message = 'Доступно только администратору.';
    protected $code = 403;

    public function __construct($message = null, $code = null)
    {
        parent::__construct($message ?? $this->message, $code ?? $this->code);
    }
}
