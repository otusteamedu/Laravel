<?php

namespace App\Http\API\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\LoginRequest;
use App\Http\Requests\Api\V2\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

/**
 * @title OAuth 2.0 Authentication
 * @description API endpoints for OAuth 2.0 authentication
 */
class OauthController extends Controller
{
    /**
     * Register new user
     *
     * @bodyParam name string required User's name. Example: John Doe
     * @bodyParam email string required User's email. Example: user@example.com
     * @bodyParam password string required User's password. Example: password
     * @bodyParam password_confirmation string required Password confirmation. Example: password
     * @response {
     *   "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
     *   "name": "John Doe"
     * }
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create($request->validated());

        $response['token'] = $user->createToken('Catalog', ['product:admin', 'category:admin'])->accessToken;
        $response['name'] = $user->name;

        return new JsonResponse($response);
    }

    /**
     * Login user
     *
     * @bodyParam email string required User's email. Example: user@example.com
     * @bodyParam password string required User's password. Example: password
     * @response {
     *   "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
     *   "name": "John Doe"
     * }
     * @response 401 {
     *   "message": "Unauthorized"
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $response['token'] = $user->createToken('Catalog', ['product:admin', 'category:admin'])->accessToken;
            $response['name'] = $user->name;

            return new JsonResponse($response);
        }

        throw new UnauthorizedException();
    }
}
