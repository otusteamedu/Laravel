<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\ExamCheckCode\DelCheckCode;

/**
 * @var string $examCheckCode одноразовый код, передаваемый преподавателю вместе с заполненным бланком экзамена
 * @var bool|null $softDelete   флаг что запись надо удалить мягко
 */

class InputDTO
{
    public function __construct(
        public string $examCheckCode,
        public bool|null $softDelete = false
    )
    {
    }
}
