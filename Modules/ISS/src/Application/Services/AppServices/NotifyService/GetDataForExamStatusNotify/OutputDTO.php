<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\NotifyService\GetDataForExamStatusNotify;

/**
 * @var string $userEmail почта пользователя ОИС, которому отправляем уведомление
 * @var string $routeName название учебного маршрута (из справочника)
 * @var string $pointName название точки учебного маршрута (из справочника)
 * @var string $examData  дата экзамена для реальной точки учебного маршрута (та что по расписанию,
 *                        не путать с фактической датой сдачи экзамена пользователем)
 */

class OutputDTO
{
    public function __construct(
        public string $userEmail,
        public string $routeName,
        public string $pointName,
        public string $examData,
    )
    {
    }
}
