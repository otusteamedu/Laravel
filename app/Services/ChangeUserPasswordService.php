<?php

namespace App\Services;

use App\Models\User;
use Hash;

class ChangeUserPasswordService
{
    public function changePassoword(User $user, string $newPassword)
    {
        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
