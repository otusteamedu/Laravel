<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp;

/**
 * @var string $tableName название таблицы в основном приложении, хранящей фио пользователя
 * @var string $fieldName название поля в таблице в основном приложении, хранящго имя пользователя
 * @var string $fieldSecondName название поля в таблице в основном приложении, хранящго отчество пользователя
 * @var string $fieldLastName название поля в таблице в основном приложении, хранящго фамилию пользователя
 */

class FioDTO
{
    public function __construct(
        public string $tableName,
        public string $fieldName,
        public string $fieldSecondName,
        public string $fieldLastName
    )
    {
    }
}
