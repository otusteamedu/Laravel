<?php

namespace App\Interface\Http\API\V1;

use App\Infrastructure\Eloquent\Models\RefreshToken;
use App\Interface\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 * name="Auth",
 * description="API Endpoints for Authentication"
 * )
 *
 * @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * in="header",
 * name="Authorization",
 * type="http",
 * scheme="bearer",
 * bearerFormat="JWT",
 * )
 *
 * @OA\Schema(
 * schema="User",
 * title="User Model",
 * description="User data",
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="name", type="string", description="User's name", example="John Doe"),
 * @OA\Property(property="email", type="string", format="email", description="User's email address", example="john@example.com"),
 * @OA\Property(property="created_at", type="string", format="date-time", readOnly="true"),
 * @OA\Property(property="updated_at", type="string", format="date-time", readOnly="true"),
 * )
 *
 * @OA\Schema(
 * schema="Tokens",
 * title="Tokens Response",
 * @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
 * @OA\Property(property="refresh_token", type="string", example="some_long_random_string..."),
 * @OA\Property(property="token_type", type="string", example="bearer"),
 * @OA\Property(property="expires_in", type="integer", example="3600"),
 * @OA\Property(property="refresh_expires_in", type="integer", example="604800"),
 * )
 *
 * @OA\Schema(
 * schema="AccessToken",
 * title="Access Token Response",
 * @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
 * @OA\Property(property="token_type", type="string", example="bearer"),
 * @OA\Property(property="expires_in", type="integer", example="3600"),
 * )
 */
class AuthController extends Controller implements HasMiddleware
{

    public static function middleware()
    {

        return [
            new Middleware('auth:jwt', except: ['login', 'refreshToken']),
        ];
    }

    /**
     * @OA\Post(
     * path="/api/v1/auth/login",
     * operationId="login",
     * tags={"Auth"},
     * summary="Get a JWT via given credentials",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"email", "password"},
     * @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     * @OA\Property(property="password", type="string", format="password", example="secret"),
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful login",
     * @OA\JsonContent(ref="#/components/schemas/Tokens")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(
     * @OA\Property(property="error", type="string", example="Unauthorized")
     * )
     * )
     * )
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
     * @OA\Post(
     * path="/api/v1/auth/me",
     * operationId="me",
     * tags={"Auth"},
     * summary="Get the authenticated User",
     * security={{"bearerAuth": {}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/User")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized"
     * )
     * )
     */
    public function me()
    {
        return response()->json(auth('jwt')->user());
    }

    /**
     * @OA\Post(
     * path="/api/v1/auth/logout",
     * operationId="logout",
     * tags={"Auth"},
     * summary="Log the user out (Invalidate the token)",
     * security={{"bearerAuth": {}}},
     * @OA\Response(
     * response=200,
     * description="Successfully logged out",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Successfully logged out")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized"
     * )
     * )
     */
    public function logout()
    {
        auth('jwt')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @OA\Post(
     * path="/api/v1/auth/refresh-token",
     * operationId="refreshToken",
     * tags={"Auth"},
     * summary="Refresh access token using refresh token",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"refresh_token"},
     * @OA\Property(property="refresh_token", type="string", example="some_long_random_string...")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Successfully refreshed tokens",
     * @OA\JsonContent(ref="#/components/schemas/Tokens")
     * ),
     * @OA\Response(
     * response=401,
     * description="Invalid or expired refresh token",
     * @OA\JsonContent(
     * @OA\Property(property="error", type="string", example="Invalid or expired refresh token")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * )
     * )
     */
    public function refreshToken(Request $request)
    {

        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        $refreshTokenModel = RefreshToken::where('token', $request->refresh_token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$refreshTokenModel) {
            return response()->json(['error' => 'Invalid or expired refresh token'], 401);
        }

        $user = $refreshTokenModel->user;

        $newAccessToken = auth('jwt')->login($user);

        $refreshTokenModel->delete();
        $newRefreshToken = $this->createRefreshToken($user);

        return $this->respondWithTokens($newAccessToken, $newRefreshToken);
    }

    /**
     * @OA\Post(
     * path="/api/v1/auth/refresh",
     * operationId="refresh",
     * tags={"Auth"},
     * summary="Refresh a token",
     * security={{"bearerAuth": {}}},
     * @OA\Response(
     * response=200,
     * description="Successfully refreshed access token",
     * @OA\JsonContent(ref="#/components/schemas/AccessToken")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * )
     * )
     */
    public function refresh()
    {
        $newToken = auth('jwt')->refresh();
        return $this->respondWithToken($newToken);
    }

    protected function createRefreshToken($user)
    {
        RefreshToken::where('user_id', $user->id)->delete();

        $refreshToken = RefreshToken::create([
            'user_id' => $user->id,
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);

        return $refreshToken->token;
    }

    protected function respondWithTokens($accessToken, $refreshToken)
    {
        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth('jwt')->factory()->getTTL() * 60,
            'refresh_expires_in' => 7 * 24 * 60 * 60,
        ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('jwt')->factory()->getTTL() * 60
        ]);
    }
}
