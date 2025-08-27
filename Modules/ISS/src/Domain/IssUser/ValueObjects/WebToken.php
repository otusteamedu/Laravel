<?php

namespace ISS\App\Domain\IssUser\ValueObjects;

use InvalidArgumentException;

/**
 * @var string|null $webToken токен авторизации пользователя в модуле ИОС
 */

final readonly class WebToken
{
    public string|null $webToken;

    public function __construct(string|null $webToken)
    {
        if (empty($webToken)) {
            throw new InvalidArgumentException("Web token cannot be empty");
        }
        $this->webToken = $webToken;
    }
}
