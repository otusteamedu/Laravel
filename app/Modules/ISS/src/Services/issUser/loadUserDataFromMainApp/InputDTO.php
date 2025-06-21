<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\OrganizationDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\FioDTO;

/**
 * @var OrganizationDTO $organization назв-я таблицы и полей для получ-я данных по организации пользов-я из основного приложения
 * @var FioDTO $fio                   назв-я таблицы и полей для получ-я данных по ФИО пользователя из основного приложения
 * @var ContactDTO $fio               назв-я таблицы и полей для получ-я данных по email пользователя из основного приложения
 * @var int $issUserId                код пользователя ИОС
 * @var array $returnedFields         массив полей, которые хотим получить из основного проложения
 */

class InputDTO
{
    public function __construct(
        public OrganizationDTO $organization,
        public FioDTO          $fio,
        public ContactDTO      $contact,
        public int             $issUserId,
        public array           $returnedFields = ['*']
    )
    {
    }
}
