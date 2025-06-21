<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\processExamCheck;

use App\Modules\ISS\src\Services\EducationExam\processExamCheck\TeacherBlankDTO;

/**
 * @var string $checkType тип проверки (auto \ manual)
 * @var string $examCheckResult результат проверки экзамена (pass\failed\sent to teacher)
 * @var TeacherBlankDTO|null $teacherBlankDTO данные для бланка экзамена, который отправляем преподавателю
 */

class OutputDTO
{
    public function __construct(
        public string $checkType,
        public string $examCheckResult,
        public TeacherBlankDTO|null $teacherBlankDTO = null,
    )
    {
    }
}
