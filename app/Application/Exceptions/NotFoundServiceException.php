<?php

namespace App\Application\Exceptions;

use Exception;

class NotFoundServiceException extends Exception
{
    protected $message = 'Запись не найдена.';
    protected $code = 404;

    public function __construct($message = null, $code = null)
    {
        parent::__construct($message ?? $this->message, $code ?? $this->code);
    }
}
