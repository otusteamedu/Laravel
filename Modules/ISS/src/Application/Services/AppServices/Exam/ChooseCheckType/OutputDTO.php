<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\ChooseCheckType;

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
