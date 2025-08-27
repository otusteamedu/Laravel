<?php

declare(strict_types=1);

namespace ISS\App\Infrastructure\Listeners;

/**
 * @var string $issUserName имя сотрудника
 * @var string $issUserSecondName отчество сотрудника
 * @var string $issUserLastName фамилия сотрудника
 * @var string $routeName название обучающего маршрута
 * @var string $pointName название справочной точки обучающего маршрута, относ-я к реальной точке для которой подходит срок сдачи экзамена
 * @var string $examDate запланированная дата экзамена
 * @var string $issUserEmail почта сотрудника, которому отправляем уведомление
 */

class SendNotifyExamDateDTO
{
    public function __construct(
        public string $issUserName,
        public string $issUserSecondName,
        public string $issUserLastName,
        public string $routeName,
        public string $pointName,
        public string $examDate,
        public string $issUserEmail,
    )
    {
    }
}
