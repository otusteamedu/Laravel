<?php

namespace App\Policy;

use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Класс политики созданный в виде набора гейтов
 * без использования автоматической генерации
 * без привязки к какой - ибо модели
 */
class MainAppUserGateSet
{
    /**
     * Проверка прав на просмотр всех пользователей основного приложения
     * замечание: чтобы использ гейт в маршрутах ОБЯЗАТЕЛЬНО нужен второй параметр хотя-бы формальный
     *            а для использования внутри контроллеров второй параметр не обязательный
     * @param User $user модель текущего пользователя
     * @return bool
     */
    public function isAdmin(User $user, string $tmp = ''): bool
    {
       if ($user->user_role == 'admin') {
           return true;
       } else {
           return false;
       }
    }

    /**
     * Проверка прав на редактирование ФИО пользователя
     * @param User $user модель текущего пользователя
     * @param int|string $userForEditId код пользователя основного приложения, которого будем редактировать
     * @return bool
     */
    public function editFio(User $user, int|string $userForEditId): bool
    {
        if ($user->id == $userForEditId || $user->user_role == 'admin') {
            return true;
        } else {
            return false;
        }
    }
}
