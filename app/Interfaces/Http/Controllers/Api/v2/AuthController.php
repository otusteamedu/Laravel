<?php

namespace App\Interfaces\Http\Controllers\Api\v2;

use App\Infrastructure\EloquentModels\User as EloquentModelsUser;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Auth\LoginRequest;
use App\Interfaces\Http\Requests\Auth\RegisterRequest;
use Http\Discovery\Psr17Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Http\Controllers\AccessTokenController;

class AuthController extends Controller
{
     /**
     * Регистрация нового пользователя
     *
     * @OA\Post(
     *     path="/api/v2/register",
     *     tags={"Auth"},
     *     summary="Регистрация пользователя",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="User"),
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Успешная регистрация",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="message", type="string", example="Успешная регистрация")
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = EloquentModelsUser::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'user'  => $user,
            'message' => 'Успешная регистрация',
        ], 201);
    }

/**
     * Авторизация пользователя (Password Grant)
     *
     * @OA\Post(
     *     path="/api/v2/login",
     *     tags={"Auth"},
     *     summary="Авторизация пользователя и получение access token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный вход",
     *         @OA\JsonContent(
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600),
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="refresh_token", type="string")
     *         )
     *     ),
     * )
     */
    public function login(
        LoginRequest $request,
        AccessTokenController $accessTokenController,
        Psr17Factory $psr17Factory,
    ): JsonResponse {
        $serverRequest = $psr17Factory->createServerRequest('POST', route('v2.oauth.token'))
            ->withParsedBody([
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ]);
        $psrResponse = $accessTokenController->issueToken(
            $serverRequest,
            $psr17Factory->createResponse()
        );
        $data = json_decode($psrResponse->getContent(), true);

        return response()->json($data, $psrResponse->getStatusCode());
    }
}
