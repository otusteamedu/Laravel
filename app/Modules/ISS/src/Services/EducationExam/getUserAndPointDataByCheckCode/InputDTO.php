<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\getUserAndPointDataByCheckCode;

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
