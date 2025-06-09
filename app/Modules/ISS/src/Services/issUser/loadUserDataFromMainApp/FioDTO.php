<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp;

/**
 * @var string $tableName       название таблицы в основном приложении, хранящей фио пользователя (ФИО)
 * @var string $fieldName       название поля таблицы ФИО, хранящго имя пользователя
 * @var string $fieldSecondName название поля таблицы ФИО, хранящго отчество пользователя
 * @var string $fieldLastName   название поля таблицы ФИО, хранящго фамилию пользователя
 * @var string $fieldCodeName   название поля в таблице Users в основном приложении, хранящго код сотрудника из ФИО
 */

class FioDTO
{
    public function __construct(
        public string $tableName,
        public string $fieldName,
        public string $fieldSecondName,
        public string $fieldLastName,
        public string $fieldCodeName
    )
    {
    }
}
