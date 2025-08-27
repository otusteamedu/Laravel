<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\IssUser\GetUserData;

/**
 * @var string $fieldName критерий поиска пользователя ИОС
 * @var string|null $fieldValue значение критерия посика (значение null используется в контроллере админ панели для сбора данных по всем поль-м)
 * @var array $returnedFields имена полей, которые хотим получить
 */

class InputDTO
{
    public function __construct(
        public string $fieldName,
        public string|null $fieldValue,
        public array $returnedFields = ['*']
    )
    {
    }
}
