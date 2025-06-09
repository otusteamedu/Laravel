<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationRoute\getRouteReadyPercentForUsersOfFirm;

/**
 * @var int|null $id          код пользователя ИОС (с ролью админ или менеджер)
 * @var bool $isIssAdmin отметка что пользователь является администратором ИОС
 */

class InputDTO
{
    public function __construct(
        public int|null $id,
        public bool $isIssAdmin
    )
    {
    }
}
