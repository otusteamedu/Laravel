<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\UserSocialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;

class YandexService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function authorize($user)
    {
        if (
            $dbUser = User::query()
            ->whereHas('socialites', function ($query) use ($user) {
                $query
                    ->where('driver', 'yandex')
                    ->where('socialite_id', $user->id);
            })
            ->first()
        ) {

            Auth::login($dbUser);
        } elseif ($dbUser = User::query()
            ->where('email', $user->email)
            ->first()
        ) {
            UserSocialite::create([
                'user_id'      => $dbUser->id,
                'driver'       => 'yandex',
                'socialite_id' => $user->id
            ]);

            Auth::login($dbUser);
        } else {
            $dbUser = User::create([
                'name' => $user->user['real_name'] ?? $user->user['display_name'],
                'email' => mb_strtolower($user->email),
                'password' => Hash::make(Str::random(10)),
            ]);

            UserSocialite::create([
                'user_id'      => $dbUser->id,
                'driver'       => 'yandex',
                'socialite_id' => $user->id
            ]);

            Auth::login($dbUser);
        }

        return $dbUser;
    }
}
