<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\getUserData;

/**
 * @var string $fieldName критерий поиска пользователя ИОС
 * @var string $fieldValue значение критерия посика
 * @var array $returnedFields имена полей, которые хотим получить
 */

class InputDTO
{
    public function __construct(
        public string $fieldName,
        public string $fieldValue,
        public array $returnedFields = ['*']
    )
    {
    }
}
