<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetRouteReadyPercentForUsersOfFirm;

/**
 * @var int|null $id          код пользователя ИОС (с ролью админ или менеджер)
 * @var bool $isIssAdmin отметка что пользователь является администратором ИОС //НЕ ИСПОЛЬЗУЕТСЯ!!!! @TODO УБРАТЬ!!!!
 */

class InputDTO
{
    public function __construct(
        public int|null $id,
        public bool $isIssAdmin //пока оставил для совместимости с прошлой версией
    )
    {
    }
}
