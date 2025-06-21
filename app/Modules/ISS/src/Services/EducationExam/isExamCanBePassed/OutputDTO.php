<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\isExamCanBePassed;

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
