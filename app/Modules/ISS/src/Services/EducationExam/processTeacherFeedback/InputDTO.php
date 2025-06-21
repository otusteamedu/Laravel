<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback;

/**
 * @var string $examCheckCode    одноразовый код проверки экзамена
 * @var string|null $examComment      комментарий преподавателя
 * @var string $examCheckResult  результат проверки экзамена преподавателем
 */

class InputDTO
{
    public function __construct(
        public string $examCheckCode,
        public string|null $examComment,
        public string $examCheckResult,
    )
    {
    }

}
