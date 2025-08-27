<?php

namespace ISS\App\Application\Services\Exam\ExamCheckCode\GetCheckCodeByUserIdAndRealPointId;

/**
 * @var string $examCheckCode одноразовый код, передаваемый преподавателю вместе с заполненным бланком экзамена
 */

class OutputDTO
{
    public function __construct(
        public string $examCheckCode
    )
    {
    }
}
