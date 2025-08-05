<?php

namespace App\Http\Controllers\Oauth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\UnauthorizedException;

class OauthController
{
    private array $rulesRegister;
    private array $rulesLogin;
    private array $errorMessagesRegister;

    public function __construct()
    {
        $this->rulesRegister = [
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'required',
            'second_name' => 'required',
            'last_name' => 'required',
            'organization' => 'required',
            'user_role' => 'required',
        ];
        $this->errorMessagesRegister = [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'email.email' => 'Invalid email',
            'name.required' => 'Name is required',
            'second_name.required' => 'second_name is required',
            'last_name.required' => 'last_name is required',
            'organization.required' => 'organization is required',
            'user_role.required' => 'user_role is required',
        ];
        $this->rulesLogin = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $this->errorMessagesLogin = [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'email.email' => 'Invalid email',
        ];
    }

    /**
     * Маршрут POST http://localhost/api/oauth/register
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->only(
            [
            'email', 'password', 'name', 'second_name', 'last_name', 'organization', 'user_role'
            ]
        ), $this->rulesRegister, $this->errorMessagesRegister);

        try {
            $user = User::query()->create($validator->validate());
        } catch (\Error | \Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }

        $response['token'] = $user->createToken('Laravel')->accessToken; //ВАЖНО!!! Здесь имя Приложения-клиента которое мы задали при создании нового клинета и которое хранится в поле oauth_clients.name
        $response['name'] = $user->name;

        return new JsonResponse($response);
    }

    /**
     * Маршрут POST http://localhost/api/oauth/login
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->only(
            [
                'email', 'password'
            ]
        ), $this->rulesLogin, $this->errorMessagesLogin);

        if (Auth::attempt($validator->validate())) {
            $user = Auth::user();
            $response['token'] = $user->createToken('Laravel', ['*'])->accessToken; //ВАЖНО!!! Здесь имя Приложения-клиента которое мы задали при создании нового клинета и которое хранится в поле oauth_clients.name
            $response['name'] = $user->name;

            return new JsonResponse($response);
        }
    }
}
