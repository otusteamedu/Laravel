<?php

declare(strict_types=1);

namespace App\Services\JwtAuth\Exceptions;

use Exception;

final class RefreshTokenNotFoundOrExpiredException extends Exception
{
    public function __construct(string $message = "Invalid or expired refresh token")
    {
        parent::__construct($message);
    }
}
