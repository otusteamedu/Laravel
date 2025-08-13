<?php

namespace App\Http\API\V1;


use App\Http\Controllers\Controller;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

/**
 * @title Authentication
 * @description API endpoints for user authentication
 */
class AuthController extends Controller implements HasMiddleware
{

    public static function middleware()
    {

        return [
            new Middleware('auth:jwt', except:  ['login', 'refreshToken']),
        ];
    }

    /**
     * User login
     *
     * @bodyParam email string required User's email. Example: user@example.com
     * @bodyParam password string required User's password. Example: password
     * @response {
     *   "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
     *   "refresh_token": "def50200f2045a5f8167e0ab25f5a5...",
     *   "token_type": "bearer",
     *   "expires_in": 3600,
     *   "refresh_expires_in": 604800
     * }
     * @response 401 {
     *   "error": "Unauthorized"
     * }
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('jwt')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth('jwt')->user();

        $refreshToken = $this->createRefreshToken($user);

        return $this->respondWithTokens($token, $refreshToken);
    }

    /**
     * Get authenticated user info
     *
     * @authenticated
     * @response {
     *   "id": 1,
     *   "name": "John Doe",
     *   "email": "john@example.com",
     *   "created_at": "2023-01-01T00:00:00.000000Z",
     *   "updated_at": "2023-01-01T00:00:00.000000Z"
     * }
     */
    public function me()
    {
        return response()->json(auth('jwt')->user());
    }

    /**
     * Logout user (invalidate token)
     *
     * @authenticated
     * @response {
     *   "message": "Successfully logged out"
     * }
     */
    public function logout()
    {
        auth('jwt')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh access token
     *
     * @bodyParam refresh_token string required Refresh token. Example: def50200f2045a5f8167e0ab25f5a5...
     * @response {
     *   "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
     *   "refresh_token": "def50200f2045a5f8167e0ab25f5a5...",
     *   "token_type": "bearer",
     *   "expires_in": 3600,
     *   "refresh_expires_in": 604800
     * }
     * @response 401 {
     *   "error": "Invalid or expired refresh token"
     * }
     */
    public function refreshToken(Request $request)
    {

        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        //$credentials = request(['email', 'password']);

        $refreshTokenModel = RefreshToken::where('token', $request->refresh_token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$refreshTokenModel) {
            return response()->json(['error' => 'Invalid or expired refresh token'], 401);
        }

        $user = $refreshTokenModel->user;

        // Генерируем новый access token
        $newAccessToken = auth('jwt')->login($user);

        // Создаем новый refresh token (опционально - можно переиспользовать старый)
        $refreshTokenModel->delete(); // Удаляем старый
        $newRefreshToken = $this->createRefreshToken($user);

        return $this->respondWithTokens($newAccessToken, $newRefreshToken);
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $newToken = auth('jwt')->refresh();
        return $this->respondWithToken($newToken);
    }

    /**
     * Create refresh token for user.
     *
     * @param \App\Models\User $user
     * @return string
     */
    protected function createRefreshToken($user)
    {
        // Удаляем старые refresh токены пользователя
        RefreshToken::where('user_id', $user->id)->delete();

        $refreshToken = RefreshToken::create([
            'user_id' => $user->id,
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7), // 7 дней
        ]);

        return $refreshToken->token;
    }

    /**
     * Get the token array structure with refresh token.
     *
     * @param string $accessToken
     * @param string $refreshToken
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithTokens($accessToken, $refreshToken)
    {
        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth('jwt')->factory()->getTTL() * 60,
            'refresh_expires_in' => 7 * 24 * 60 * 60, // 7 дней в секундах
        ]);
    }

    /**
     * Get the token array structure (старый метод).
     *
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('jwt')->factory()->getTTL() * 60
        ]);
    }
}
