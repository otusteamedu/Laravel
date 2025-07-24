<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Авторизация и регистрация пользователей"
 * )
 *
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     type="object",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="name", type="string", example="Иван Иванов"),
 *     @OA\Property(property="email", type="string", format="email", example="ivan@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="password123"),
 * )
 *
 * @OA\Schema(
 *     schema="LoginRequest",
 *     type="object",
 *     required={"email", "password"},
 *     @OA\Property(property="email", type="string", format="email", example="ivan@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="password123"),
 * )
 *
 * @OA\Schema(
 *     schema="AuthTokenResponse",
 *     type="object",
 *     @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGci..."),
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="error", type="string", example="Invalid credentials"),
 * )
 *
 * @OA\Post(
 *     path="/api/v2/register",
 *     summary="Регистрация нового пользователя",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Успешная регистрация",
 *         @OA\JsonContent(ref="#/components/schemas/AuthTokenResponse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Ошибка валидации",
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v2/login",
 *     summary="Авторизация пользователя",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Успешный вход, возвращает токен",
 *         @OA\JsonContent(ref="#/components/schemas/AuthTokenResponse")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Неверные учетные данные",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v2/logout",
 *     summary="Выход пользователя (отзыв токена)",
 *     tags={"Auth"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Успешный выход",
 *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Logged out"))
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Неавторизован",
 *     )
 * )
 *
 */
class AuthApiAnnotations
{
    // Класс нужен чтобы swagger-php обрабатывал аннотации
}
