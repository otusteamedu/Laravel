<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\IssUser\GetAllUsersByOrganization;

/**
 * @var string $organization название организации пользователя ИОС
 */

class InputDTO
{
    public function __construct(
        public string $organization,
    )
    {
    }
}
