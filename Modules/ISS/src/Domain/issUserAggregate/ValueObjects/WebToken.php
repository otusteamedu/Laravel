<?php

namespace Modules\ISS\Domain\issUserAggregate\ValueObjects;

use InvalidArgumentException;

/**
 * @var string|null $webToken токен авторизации пользователя в модуле ИОС
 */

final readonly class WebToken
{
    private string|null $webToken;

    public function __construct(string|null $webToken)
    {
        if (empty($webToken)) {
            throw new InvalidArgumentException("Web token cannot be empty");
        }
        $this->webToken = $webToken;
    }
}
