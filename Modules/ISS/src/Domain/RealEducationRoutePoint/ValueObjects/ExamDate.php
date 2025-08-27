<?php

namespace ISS\App\Domain\RealEducationRoutePoint\ValueObjects;

use InvalidArgumentException;

/** @var string $examDate дата экзамена */

final readonly class ExamDate
{
    public string $examDate;

    public function __construct(string $examDate)
    {
        if (empty($examDate)){
            throw new InvalidArgumentException("Real route point exam date must be not empty");
        }
        //if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $examDate) === 0) {
        //    throw new InvalidArgumentException("Real route point exam date must have format \'YYYY-MM-DD\'");
        //}
        $this->examDate = $examDate;
    }

}
