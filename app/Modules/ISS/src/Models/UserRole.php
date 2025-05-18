<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use app\Modules\ISS\src\Models\UserData;
use App\Modules\ISS\database\factories\UserRoleFactory;

class UserRole extends BaseModel
{
    /**
     * Поля модели:
     * id -- код роли пользователя (unsignedBigInteger)
     * name -- название роли пользователя (string)
     */

    protected $fillable = ['name'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return UserRoleFactory::new();
    }

    public function userData()
    {
        return $this->hasMany(UserData::class, 'role_id');
    }
}
