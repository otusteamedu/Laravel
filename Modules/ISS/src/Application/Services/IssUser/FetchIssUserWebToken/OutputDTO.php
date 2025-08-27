<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\IssUser\FetchIssUserWebToken;

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
