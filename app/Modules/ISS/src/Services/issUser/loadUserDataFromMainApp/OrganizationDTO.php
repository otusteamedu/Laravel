<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp;

/**
 * @var string $tableName название таблицы в основном приложении, хранящей название организации пользователя
 * @var string $fieldOrganizationName название поля в таблице в основном приложении, хранящго название организации
 * @var string $organizationCode название поля в таблице в основном приложении, хранящго код организации (первичн чключ)
 */

class OrganizationDTO
{
    public function __construct(
        public string $tableName,
        public string $fieldOrganizationName,
        public string $organizationCode
    )
    {
    }
}
