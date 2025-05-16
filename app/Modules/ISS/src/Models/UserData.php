<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\UserRole;
use App\Models\User;
use App\Modules\ISS\database\factories\UserDataFactory;

class UserData extends BaseModel
{
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
