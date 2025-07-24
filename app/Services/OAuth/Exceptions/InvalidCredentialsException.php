<?php

declare(strict_types=1);

namespace App\Services\OAuth\Exceptions;

use Exception;

final class InvalidCredentialsException extends Exception
{
    public function __construct(string $message = "Invalid credential")
    {
        parent::__construct($message);
    }
}
