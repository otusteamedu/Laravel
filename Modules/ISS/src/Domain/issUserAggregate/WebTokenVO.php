<?php

namespace Modules\ISS\Domain\issUserAggregate;

use \Exception;

/**
 * @var string|null $webToken токен авторизации пользователя в модуле ИОС
 */

class WebTokenVO
{
    private string|null $webToken;

    public function __construct(string|null $webToken)
    {
        $this->webToken = $webToken;
    }
}
