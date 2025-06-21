<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\UserRole;
use App\Modules\ISS\src\Models\RealEducationRoutesOfUser;
use App\Modules\ISS\database\factories\UserDataFactory;

/**
 * Поля модели:
 * @property integer $id -- код данных пользователя
 * @property integer $user_id -- ссылка на код пользователя из основного приложения
 * @property integer $role_id -- ссылка на роль пользователя в ИОС
 * @property string $user_iss_login -- логин пользователя в ИОС
 * @property string $user_iss_password -- пароль пользователя в ИОС
 * @property string $user_iss_avatar_path -- путь к файлу пользователя в ИОС
 * @property string $organization -- название организации, в которой работает сотрудник (загружается из основного приложения)
 * @property string $name -- имя сотрудника (загружается из основного приложения)
 * @property string $second_name -- отчество сотрудника (загружается из основного приложения)
 * @property string $last_name -- фамилия сотрудника (загружается из основного приложения)
 * @property string $email -- почта пользователя ИОС (загружается из основного приложения)
 * @property string $web_token -- жетон авторизации ИОС
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @property \datetime $deleted_at
 */

class UserData extends BaseModel
{
    protected $fillable = ['user_id', 'role_id', 'user_iss_avatar_path',
        'organization', 'name', 'second_name', 'last_name', 'web_token', 'email'];
    //protected $hidden = ['user_iss_login', 'user_iss_password'];
    //protected $casts = ['created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return UserDataFactory::new();
    }

    //связи
    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    public function realEducationRoutesOfUser()
    {
        return $this->hasMany(RealEducationRoutesOfUser::class, 'user_data_id');
    }

    public function examCheckCode()
    {
        return $this->hasMany(ExamCheckCode::class, 'iss_user_id');
    }
}
