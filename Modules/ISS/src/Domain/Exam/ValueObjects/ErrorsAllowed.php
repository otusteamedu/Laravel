<?php

namespace ISS\App\Domain\Exam\ValueObjects;

use InvalidArgumentException;

/**
 * @var int $errorsAllowed процент допустимых ошибок в тесте
 */

class ErrorsAllowed
{
    public int $errorsAllowed;

    public function __construct(int $errorsAllowed)
    {
        if ($errorsAllowed < 0) {
            throw new InvalidArgumentException("ErrorsAllowed value must be positive or 0");
        }
        $this->errorsAllowed = $errorsAllowed;
    }
}
