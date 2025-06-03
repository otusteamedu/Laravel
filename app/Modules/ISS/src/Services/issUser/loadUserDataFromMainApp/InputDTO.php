<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\OrganizationDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\FioDTO;

/**
 * @var OrganizationDTO $organization данные по таблице организации пользователя из основного приложения
 * @var FioDTO $fio данные по таблице ФИО пользователя из основного приложения
 * @var int $issUserId код пользователя ИОС
 * @var int $user_id код пользователя в основном приложении
 */

class InputDTO
{
    public function __construct(
        public OrganizationDTO $organization,
        public FioDTO          $fio,
        public int             $issUserId,
        public int             $user_id
    )
    {
    }
}
