<?php

namespace App\Modules\ISS\src\Services\EducationExam\getCheckCodeByUserIdAndRealPointId;

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
