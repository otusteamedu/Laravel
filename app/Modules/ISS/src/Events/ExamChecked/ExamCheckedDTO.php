<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Events\ExamChecked;

use App\Modules\ISS\src\Services\EducationExam\fillExamBlank\QuestionWithAnswersWithTextDTO;


/**
 * @var bool $needMailToTeacher флаг что нужно отправить письмо преподавателю (с бланклм экзамена на проверку)
 * @var string|null $teacherEmail почта преподавателя
 * @var string|null $teacherURL защищенная ссылка для отправки результатов, после проверки бланка экзамена преподом
 * @var string|null $examCheckCode одноразовый персональный код проверки экзамена для преподавателя
 * @var array<QuestionWithAnswersWithTextDTO>|null $checkedQuestionsWithText бланк экзам. вопросов с ответами ученика и правильными ответами (для простых вопросов)
 * @var string $studentEmail почта ученика
 * @var string $scheduledExamDate запланированная дата сдачи экзамена (не фактическая!)
 * @var string $pointName название точки обучающего маршрута (для которой сдается экзамен)
 * @var string $routeName название обучающего маршрута (для которого сдается экзамен)
 * @var string $examCheckResult текущий статус экзамена (сдан\не сдан\отправлен на проверку преподу)
 */

class ExamCheckedDTO
{
    public  function  __construct(
        public bool $needMailToTeacher,
        public string|null $teacherEmail,
        public string|null $teacherURL,
        public string|null $examCheckCode,
        public array|null $checkedQuestionsWithText,
        public string $studentEmail,
        public string $scheduledExamDate,
        public string $pointName,
        public string $routeName,
        public string $examCheckResult
    )
    {
    }
}
