<?php

namespace App\Exceptions\Fibonachi;

use Exception;

class FibonachiExaption extends Exception
{
    protected $message = 'Число должно быть от 1 до 100.';

    public function __construct($message = null, $code = 400)
    {
        parent::__construct($message ?? $this->message, $code);
    }
}
