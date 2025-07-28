<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class OauthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create($request->validated());

        $response['token'] = $user->createToken('Laravel')->accessToken;
        $response['name'] = $user->name;

        return new JsonResponse($response);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $response['token'] = $user->createToken('Laravel', ['blogs:create'])->accessToken;
            $response['name'] = $user->name;

            return new JsonResponse($response);
        }

        throw new UnauthorizedException;
    }
}
