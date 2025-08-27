<?php

namespace ISS\App\Domain\RealEducationRoutePoint;

use ISS\App\Domain\SharedValueObjects\Id;
use ISS\App\Domain\RealEducationRoutePoint\ValueObjects\ExamDate;
use ISS\App\Domain\RealEducationRoutePoint\ValueObjects\Position;

/**
 * @var Id $id код реальной точки маршрута
 * @var Id $routePointId код справочной точки обучающего маршрута
 * @var Id $routeId код справочного маршрута
 * @var ExamDate $examDate дата экзамена для этой точки маршрута
 * @var Position $position позиция точки в реальном обучающем маршрута
 */

class RealEducationRoutePoint
{
    private Id $id;
    private Id $routePointId;
    private Id $routeId;
    private ExamDate $examDate;
    private Position $position;
    private array $allowedExamResults;

    public function __construct(int $id, int $routePointId, int $routeId, string $examDate, int $position)
    {
        $this->id = new Id($id);
        $this->routePointId = new Id($routePointId);
        $this->routeId =  new Id($routeId);
        $this->examDate = new ExamDate($examDate);
        $this->position = new Position($position);
        $this->allowedExamResults = [
            'passed' => 'passed',
            'expired' => 'expired',
            'wait' => 'wait',
        ];
    }

    //геттеры
    public function getId(): Id
    {
        return $this->id;
    }

    public function getRoutePointId(): Id
    {
        return $this->routePointId;
    }

    public function getRouteId(): Id
    {
        return $this->routeId;
    }

    public function getExamDate(): ExamDate
    {
        return $this->examDate;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    //МУТАТОРЫ

    //БИЗНЕС ПРАВИЛА

    /**
     * Проверка статуса реальнойточки учебного маршрута
     * (пройдена или нет, т.е. сдан экзамен или нет)
     * @param int|null $LppPosition позиция последней пройденной точки в учебном маршруте
     * @return string
     */
    public function pointStatus(int|null $LppPosition): string
    {
        $LppPosition = new Position($LppPosition);

        $currentDate = date('Y-m-d');

        $currentPointPosition = $this->position->position;
        $currentPointExamDate = $this->examDate;

        if (is_null($LppPosition->position) || $currentPointPosition > $LppPosition->position) {
            if (date('Y-m-d', strtotime($currentPointExamDate->examDate)) >=
                date('Y-m-d', strtotime($currentDate))
            ) {
                return $this->allowedExamResults['wait'];
            } else {
                return $this->allowedExamResults['expired'];
            }
        } else {
            return $this->allowedExamResults['passed'];
        }
    }

    /**
     * Проверка реальнойточки учебного маршрута для подсчета прохождения маршрута
     * (пройдена или нет)
     * @param int|null $LppPosition позиция последней пройденной точки в учебном маршруте
     * @return bool
     */
    public function isPassed(int|null $LppPosition): bool
    {
        $LppPosition = new Position($LppPosition);

        if (!is_null($LppPosition->position)) {
            if ($LppPosition->position >= $this->position->position) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

}
