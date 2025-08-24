<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\JsonResponse;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    /**
     * Аутентификация пользователя
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'error' => 'Неверные учетные данные'
            ], 401);
        }

        return response()->json([
            'user' => auth('api')->user(),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    /**
     * Выход из системы
     */
    public function logout(): JsonResponse
    {
        JWTAuth::logout();

        return response()->json([
            'message' => 'Успешный выход из системы'
        ]);
    }

    /**
     * Обновление токена
     */
    public function refresh(): JsonResponse
    {
        $token = JWTAuth::refresh();

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}

