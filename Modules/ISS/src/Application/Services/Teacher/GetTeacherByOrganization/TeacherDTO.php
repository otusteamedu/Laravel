<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Teacher\GetTeacherByOrganization;

/**
 * @var int $id код преподавателя
 * @var string $connectedOrganization название организации, к которой относится преподаватель
 * @var string $teacherEmail почта преподавателя
 */

class TeacherDTO
{
    public function __construct(
        public int $id,
        public string $connectedOrganization,
        public string $teacherEmail,
    )
    {
    }
}
