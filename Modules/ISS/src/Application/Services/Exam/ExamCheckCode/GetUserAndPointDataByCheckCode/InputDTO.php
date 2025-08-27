<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\ExamCheckCode\GetUserAndPointDataByCheckCode;

/**
 * @var string $examCheckCode одноразовый код, передаваемый преподавателю вместе с заполненным бланком экзамена
 */

class InputDTO
{
    public function __construct(
        public string $examCheckCode
    )
    {
    }
}
