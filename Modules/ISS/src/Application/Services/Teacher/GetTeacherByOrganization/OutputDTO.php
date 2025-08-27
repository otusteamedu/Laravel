<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Teacher\GetTeacherByOrganization;

/**
 * @var array $teachers массив всех преподавателей, относящихся к указанной организации
 */

class OutputDTO
{
    public function __construct(
        public array $teachers,
    )
    {
    }
}
