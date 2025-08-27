<?php

namespace ISS\App\Infrastructure\Listeners;

use App\Modules\ISS\src\Services\EducationRoutePoint\getAllRealRoutePoints\GetAllRealRoutePoints;
use ISS\App\Application\Services\AppServices\IssUser\GetUsersBelongsToRoute\GetUsersBelongsToRoute;
use ISS\App\Application\Services\AppServices\IssUser\GetUsersBelongsToRoute\InputDTO as getUsersInputDTO;
use ISS\App\Infrastructure\Listeners\SendNotifyExamDateDTO;
use ISS\App\Infrastructure\Events\CheckExamDates\CheckExamDates;
use ISS\App\Infrastructure\Jobs\SendExamDateComeNotifyJob;

/**
 * Проверяет запланированные даты экзаменов для всех точек реальных обучающих маршрутов,
 * в случае если запланированная дата экзамена отличается от текущей (в большую сторону)
 * на интервал, указанный в конфигурации сервиса, то отправляет уведомление обучающимся
 * на этом учебном маршруте о том, что приближается срок сдачи очередного экзамена.
 */

class SendNotifyExamDateListener
{
    private GetAllRealRoutePoints $getAllRealRoutePoints;
    private GetUsersBelongsToRoute $getUsersBelongsToRoute;

    /**
     * Create the event listener.
     */
    public function __construct(
        GetAllRealRoutePoints $getAllRealRoutePoints,
        GetUsersBelongsToRoute $getUsersBelongsToRoute
    )
    {
        $this->getAllRealRoutePoints = $getAllRealRoutePoints;
        $this->getUsersBelongsToRoute = $getUsersBelongsToRoute;
    }

    /**
     * Handle the event.
     */
    public function handle(
        CheckExamDates $event,
        //GetAllRealRoutePoints $getAllRealRoutePoints,
        //GetUsersBelongsToRoute $getUsersBelongsToRoute,
    ): void
    {
        //достать все реальные точки обучающих маршрутов
        try {
            $allRealRoutePoints = ($this->getAllRealRoutePoints)();
        } catch (\Error | \Exception $e) {
            //запись в лог
            $allRealRoutePoints = [];
        }

        //в цикле проход по всем реальным точкам маршрутов
        foreach ($allRealRoutePoints as $point) {
            $interval = round(strtotime($point->examDate) - strtotime($event->dto->currentDate))/(60*60*24);

            if (
                //проверка что если дата экзамена - текущая дата = интервал из конфигурации
                strtotime($point->examDate) > strtotime($event->dto->currentDate)
                &&
                $interval == config('iss.EXAM_COME_SOON_INTERVAL')
            ) {

                //найти всех пользователей ИОС относящихся к этому маршруту
                try {
                    $usersBelongsToRoute = ($this->getUsersBelongsToRoute)(new getUsersInputDTO(routeId: $point->routeId));
                } catch (\Error | \Exception $e) {
                    //запись в лог
                    $usersBelongsToRoute = [];
                }

                //каждому из пользователей отправить уведомление что приближается срок сдачи экзамена
                foreach ($usersBelongsToRoute as $user) {
                    SendExamDateComeNotifyJob::dispatch(
                        new SendNotifyExamDateDTO(
                            issUserName: $user->name,
                            issUserSecondName: $user->secondName,
                            issUserLastName: $user->lastName,
                            routeName: $point->routeName,
                            pointName: $point->pointName,
                            examDate: $point->examDate,
                            issUserEmail: $user->email,
                        )
                    )->onQueue('iss')->delay(5);
                }
            }
        }
    }
}
