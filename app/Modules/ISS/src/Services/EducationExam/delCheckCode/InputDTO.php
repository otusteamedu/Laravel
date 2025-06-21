<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\delCheckCode;

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
