<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\IsExamCanBePassed;

/**
 * @var bool $grantPassExam разрешение сдать экзамен (false -- нельзя \ true -- можно)
 */

class OutputDTO
{
    public function __construct(
        public bool $grantPassExam,
    )
    {
    }

}
