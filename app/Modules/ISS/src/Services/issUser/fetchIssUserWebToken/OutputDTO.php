<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\fetchIssUserWebToken;

/**
 * @var string|null $issUserWebToken защитный токен модели пользователя ИОС для входа в веб
*/

class OutputDTO
{
    public function __construct(
        public string|null $issUserWebToken = null,
    )
    {
    }
}
