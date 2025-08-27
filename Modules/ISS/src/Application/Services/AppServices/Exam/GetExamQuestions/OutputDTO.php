<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\GetExamQuestions;

use ISS\App\Application\Services\AppServices\Exam\GetExamQuestions\AnswerDTO;

/**
 * @var int $rerpId               код реальной точки обучающего маршрута
 * @var int $erpId                код точки обучающего маршрута из справочника
 * @var int $questionId           код вопроса для контрольного теста (для этой точки обучающего маршрута)
 * @var string $questionName      название вопроса для контрольного теста (для этой точки обучающего маршрута)
 * @var string $questionText      текст вопроса для контрольного теста (для этой точки обучающего маршрута)
 * @var array<AnswerDTO> $answers ответы на вопрос контрольного теста
 */

class OutputDTO
{
    public function __construct(
        public int       $rerpId,
        public int       $erpId,
        public int       $questionId,
        public string    $questionName,
        public string    $questionText,
        public array     $answers,
    )
    {
    }
}
