<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\chooseExamCheckTeacher;

/**
 * @var string|null $email почта преподавателя которому будет отправлен экзамен на проверку
 */

class OutputDTO
{
    public function __construct(
        public string|null $email
    )
    {
    }
}
