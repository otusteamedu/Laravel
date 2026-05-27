<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserPasswordService
{
    public function updatePassword(User $user, string $newPassword)
    {
        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
