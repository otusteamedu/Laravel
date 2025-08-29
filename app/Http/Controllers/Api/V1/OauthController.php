<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\LoginRequest;
use App\Http\Requests\Api\v1\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class OauthController extends Controller
{
    use HasApiTokens;
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create($request->validated());

        $response['token'] = $user->createToken('laravel')->accessToken;
        $response['name'] = $user->name;

        return new JsonResponse($response);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $response['token'] = $this->createToken('laravel', ['news:create'])->accessToken;
            $response['name'] = $user->name;

            return new JsonResponse($response);
        }

        throw new UnauthorizedException();
    }
}
