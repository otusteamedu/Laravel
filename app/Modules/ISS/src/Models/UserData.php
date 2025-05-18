<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\UserRole;
use App\Modules\ISS\src\Models\RealEducationRoutesOfUser;
use App\Models\User;
use App\Modules\ISS\database\factories\UserDataFactory;

class UserData extends BaseModel
{
    /**
     * Поля модели:
     * id -- код данных пользователя (unsignedBigInteger)
     * user_id -- ссылка на код пользователя из основного приложения (unsignedBigInteger)
     * role_id -- ссылка на роль пользователя в ИОС (unsignedBigInteger)
     * user_iss_login -- логин пользователя в ИОС (string)
     * user_iss_password -- пароль пользователя в ИОС (string)
     */

    protected $fillable = ['user_id', 'role_id', 'user_iss_login', 'user_iss_password'];
    protected $hidden = ['user_iss_login', 'user_iss_password'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return UserDataFactory::new();
    }

    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    public function realEducationRoutesOfUser()
    {
        return $this->hasMany(RealEducationRoutesOfUser::class, 'user_data_id');
    }
}
