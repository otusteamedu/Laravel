<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp;

/**
 * @var string $tableName             название таблицы в основном приложении, хранящей название организации пользователя (ОРГ)
 * @var string $fieldOrganizationName название поля ОРГ, хранящго название организации
 * @var string $fieldCodeName         название поля в таблице Users в основном приложении, хранящго код организации из ОРГ
 */

class OrganizationDTO
{
    public function __construct(
        public string $tableName,
        public string $fieldOrganizationName,
        public string $fieldCodeName
    )
    {
    }
}
