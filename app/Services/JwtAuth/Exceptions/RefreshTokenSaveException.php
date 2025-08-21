<?php

declare(strict_types=1);

namespace App\Services\JwtAuth\Exceptions;

use Exception;

final class RefreshTokenSaveException extends Exception
{
    public function __construct(string $message = "Не удалось сохранить токен")
    {
        parent::__construct($message);
    }
}
