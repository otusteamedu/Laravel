<?php

namespace ISS\App\Infrastructure\Models;

use ISS\App\Infrastructure\Models\BaseModel;
use ISS\App\Infrastructure\Models\UserData;
use ISS\Database\Factories\UserRoleFactory;

/**
 * Поля модели:
 * @property integer $id -- код роли пользователя
 * @property string $name -- название роли пользователя
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @property \datetime $deleted_at
 */

class UserRole extends BaseModel
{
    protected $fillable = ['name'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return UserRoleFactory::new();
    }

    //связи
    public function userData()
    {
        return $this->hasMany(UserData::class, 'role_id');
    }
}
