<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp;

/**
 * @var string $tableName       название таблицы в основном приложении, хранящей фио пользователя (ФИО)
 * @var string $fieldEmail      название поля таблицы с контактами в основном приложении, хранящго email пользователя
 * @var string $fieldCodeName   название поля в таблице Users в основном приложении, хранящго код сотрудника из ФИО
 */

class ContactDTO
{
    public function __construct(
        public string $tableName,
        public string $fieldEmail,
        public string $fieldCodeName
    )
    {
    }
}
