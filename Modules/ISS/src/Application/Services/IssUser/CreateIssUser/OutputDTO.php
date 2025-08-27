<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\IssUser\CreateIssUser;

/**
 * @var bool $result результат операции обновления пользователя ИОС
 */

class OutputDTO
{
    public function __construct(
        public bool $result
    )
    {
    }
}
