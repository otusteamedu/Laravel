<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Teacher\GetTeacherByOrganization;

/**
 * @var string $organization название организации, к которой относится преподаватель
 */

class InputDTO
{
    public function __construct(
        public string $organization,
    )
    {
    }
}
