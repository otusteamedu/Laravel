<?php

declare(strict_types=1);

namespace ISS\App\Infrastructure\Events\CheckExamDates;

/**
 * @var string $currentDate текущая дата
 */

class CheckExamDatesDTO
{
    public function __construct(
        public string $currentDate,
    )
    {
    }
}
