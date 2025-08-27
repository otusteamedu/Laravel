<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\IssUser\GetUsersBelongsToRoute;

/**
 * @var string $email почта пользователя ИОС
 * @var string $name имя пользователя ИОС
 * @var string $secondName отчество пользователя ИОС
 * @var string $lastName фамилия пользователя ИОС
 */

class OutputDTO
{
    public function __construct(
        public string $email,
        public string $name,
        public string $secondName,
        public string $lastName,
    )
    {
    }
}
