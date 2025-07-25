<?php

namespace App\Http\API\V1\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\API\V1\Requests\Auth\LoginRequest;
use App\Http\API\V1\Requests\Auth\CreateUserRequest;


class OAuthController extends Controller
{
    /**
     * Регистрация пользователя
     * @param \App\Http\API\V1\Requests\Auth\CreateUserRequest $request
     * @return JsonResponse
     */
    public function register(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::query()->create($data);

        return response()->json(['message' => 'User registerd successfully'], 201);
    }

    /**
     * Авторизация
     * @param \App\Http\API\V1\Requests\Auth\LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->accessToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'The provided credentials are incorrect.'], 401);
    }

    /**
     * Сведения о залогиненом пользователе
     * @return UserResource
     */
    public function user(): UserResource
    {
        $user = Auth::user();

        return new UserResource($user);
    }

    /**
     * Выход
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
