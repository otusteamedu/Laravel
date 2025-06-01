<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\chooseCheckType;

/**
 * @var string $checkType тип проверки теста (manual \ auto)
 */

class OutputDTO
{
    public function __construct(
        public string $checkType
    )
    {
    }
}
