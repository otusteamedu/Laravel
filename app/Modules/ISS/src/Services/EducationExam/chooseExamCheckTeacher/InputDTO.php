<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\chooseExamCheckTeacher;

/**
 * @var int $issUserId код пользователя ИОС, экзамен которого проверяем
 */

class InputDTO
{
    public function __construct(
        public int $issUserId
    )
    {
    }
}
