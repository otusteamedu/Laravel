<?php

namespace App\Domain\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $message = 'Запись не найдена.';

    public function __construct($message = null, $code = 404)
    {
        parent::__construct($message ?? $this->message, $code);
    }
}
