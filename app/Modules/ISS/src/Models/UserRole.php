<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use app\Modules\ISS\src\Models\UserData;
use App\Modules\ISS\database\factories\UserRoleFactory;

/**
 * Поля модели:
 * @property integer $id -- код роли пользователя
 * @property string $name -- название роли пользователя
 * @property datetime $created_at
 * @property datetime $updated_at
 * @property datetime $deleted_at
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

    public function userData()
    {
        return $this->hasMany(UserData::class, 'role_id');
    }
}
