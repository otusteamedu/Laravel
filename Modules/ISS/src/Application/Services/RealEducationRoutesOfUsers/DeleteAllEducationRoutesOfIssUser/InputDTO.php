<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\DeleteAllEducationRoutesOfIssUser;

/**
 * @var int $issUserId код пользователя ИОС для которого удаляем все его реальные обучающие маршруты
 */

class InputDTO
{
    public function __construct(
        public int $issUserId,
    )
    {
    }
}
