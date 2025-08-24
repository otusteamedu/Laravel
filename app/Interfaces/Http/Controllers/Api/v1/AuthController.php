<?php

namespace App\Interfaces\Http\Controllers\Api\v1;

use App\Infrastructure\EloquentModels\User as EloquentModelsUser;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Auth\LoginRequest;
use App\Interfaces\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Регистрация нового пользователя
     */
    public function register(RegisterRequest $request): JsonResponse 
    {
        $user = EloquentModelsUser::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Авторизация пользователя
     */
    public function login(LoginRequest $request): JsonResponse 
    {
        $request->authenticate();

        $user = $request->user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }
}
