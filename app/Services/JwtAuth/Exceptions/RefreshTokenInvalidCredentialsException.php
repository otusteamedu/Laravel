<?php

declare(strict_types=1);

namespace App\Services\JwtAuth\Exceptions;

use Exception;

final class RefreshTokenInvalidCredentialsException extends Exception
{
    public function __construct(string $message = "Invalid credential")
    {
        parent::__construct($message);
    }
}
