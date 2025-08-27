<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\IssUser\DeleteIssUser;

/**
 * @var int $issUserId код пользователя ИОС
 */

class InputDTO
{
    public function __construct(
        public int $issUserId
    )
    {
    }
}
