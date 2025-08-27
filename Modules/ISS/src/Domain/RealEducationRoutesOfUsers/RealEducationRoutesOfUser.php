<?php

namespace ISS\App\Domain\RealEducationRoutesOfUsers;

use ISS\App\Domain\SharedValueObjects\Id;

/**
 * @var Id $id код реального маршрута пользователя ИОС
 * @var Id $userDataId код пользователя ИОС
 * @var Id $routeId код справочного маршрута на который ссылается текущий реальный маршрут
 * @var Id $lastPassPointId код последней пройденной реальной точки маршрута (для нее экзамен сдан)
 */

class RealEducationRoutesOfUser
{
    private Id $id;
    private Id $userDataId;
    private Id $routeId;
    private Id|null $lastPassPointId;

    public function __construct(
        int $id, int $userDataId, int $routeId, int|null $lastPassPointId
    )
    {
        $this->id = new Id($id);
        $this->userDataId = new Id($userDataId);
        $this->routeId = new Id($routeId);
        $this->lastPassPointId = !is_null($lastPassPointId) ? new Id($lastPassPointId) : null;
    }

    //геттеры
    public function getId(): Id
    {
        return $this->id;
    }

    public function getUserDataId(): Id
    {
        return $this->userDataId;
    }

    public function getRouteId(): Id
    {
        return $this->routeId;
    }

    public function getLastPassPointId(): Id
    {
        return $this->lastPassPointId;
    }

    //сеттеры
    //бизнес правила

    /**
     * Расчет доли пройденной части маршрута в процентах
     * @param int $pointsCount общее кол-во точек на реальном маршруте
     * @param int $passedPointsCount количество пройденных точек
     * @return float
     */
    public function readyPercent(int $pointsCount, int $passedPointsCount): float
    {
        $tmp = is_null($this->lastPassPointId);
        if ($tmp || $pointsCount === 0) {
            return 0;
        } else {
            return round(100 * ($passedPointsCount / $pointsCount), 0);
        }
    }
}
