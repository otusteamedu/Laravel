<?php

namespace App\Exceptions\Fibonachi;

use Exception;

class NotAdminException extends Exception
{
    protected $message = 'Доступно только администратору.';

    public function __construct($message = null, $code = 403)
    {
        parent::__construct($message ?? $this->message, $code);
    }
}
