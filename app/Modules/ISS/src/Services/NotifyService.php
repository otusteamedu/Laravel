<?php

namespace App\Modules\ISS\src\Services;

use Illuminate\Database\Eloquent\Collection as eqCollection;

class NotifyService
{
    /**
     * Отправить оповещение что зарегистрирован новый пользователь ИОС
     * @param array $inputData данные пользователя ИОС + users данные главного приложения
     * @return string
     */
    public function notifyNewIssUserCreated(array $inputData): string
    {
        $result = null;
        //отправка оповещения на mail через очередь если у нового пользователя есть регистрация в главном приложении
        return $result;
    }

    /**
     * Отправить mail пользователю ИОС что тетс пройден, если пользователь зареган в главном приложении
     * @param array $inputData код пользователя ИОС, код реальной точки учебного маршрута
     * @return
     */
    public function mailTestPassed(array $inputData)
    {
        $result = null;
        //
        return $result;
    }

    /**
     * Отправить mail преподователю, что тест ожидает проверки
     * @param array $inputData код реальной точки маршрута, запрос из формы с тестом
     * @return
     */
    public function mailTestWaitingChecking(array $inputData)
    {
        $result = null;
        //
        return $result;
    }

    /**
     * Отправить оповещение пользователю ИОС, если он зареган в главном проложении, что подходит срок сдачи теста
     * @param array $inputData код поьзователя ИОС
     * @return
     */
    public function notifyTestWaiting(array $inputData)
    {
        $result = null;
        //
        return $result;
    }
}
