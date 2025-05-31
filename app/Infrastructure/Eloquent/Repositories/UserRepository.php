<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\Repositories\UserDTO;
use App\Services\Repositories\UserCreateDTO;
use App\Services\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function fetchAll(): array
    {
        return User::all()->all();
    }

    public function find(int $id): ?UserDTO
    {
        $dbUser = User::query()
            ->where('id', $id)
            ->first();

        if ($dbUser === null) {
            return null;
        }

        return new UserDTO(
            id: $dbUser->id,
            name: $dbUser->name,
            email: $dbUser->email,
        );
    }

    public function add(UserCreateDTO $user): int
    {
        $dbUser = User::create([
            'name'              => $user->name,
            'email'             => $user->email,
            'password'          => $user->password,
            'email_verified_at' => $user->email_verified_at
        ]);

        return $dbUser->refresh()->id;
    }

    public function findByEmail(string $email): ?UserDTO
    {
        $dbUser = User::query()
            ->where('email', $email)
            ->first();

        if ($dbUser === null) {
            return null;
        }

        return new UserDTO(
            id: $dbUser->id,
            name: $dbUser->name,
            email: $dbUser->email,
        );
    }

    public function login(int $id, $remeber = false): void
    {
        $dbUser = User::findOrFail($id);

        Auth::login($dbUser, $remeber);
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
