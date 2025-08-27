<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\ProcessTeacherFeedback;

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
