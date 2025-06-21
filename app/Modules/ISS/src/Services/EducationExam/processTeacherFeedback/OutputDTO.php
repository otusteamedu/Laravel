<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback;

/**
 * @var string $examResult текст с результатом экзамена для отправки ученику
 */

class OutputDTO
{
    public function __construct(
        public string $examResult,
        public int $issUserId,
        public int $realRoutePointId,
    )
    {
    }
}
