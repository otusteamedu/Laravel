<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetRouteReadyPercentForUsersOfFirm;
/**
 * @var string $organization название организации для текущего пользователя (с ролью менеджер или админ)
 * @var array[][] $employees степернь прохождения каждым сотрудником всех обучающих маршрутов, к которым он подключен
 *            двумерный массив имеет вид
 *            [
 *             'fio1' => ['routeName1' => readyPercent1, 'routeName2' => readyPercent2, ...],
 *             'fio2' => ['routeName1' => readyPercent1, 'routeName2' => readyPercent2, ...],
 *               ...
 *            ]
 */
class OutputDTO
{
    public function __construct(
        public string $organization,
        public array  $employees
    )
    {
    }
}
