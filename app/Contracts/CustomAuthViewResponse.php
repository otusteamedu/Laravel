<?php

declare(strict_types=1);

namespace App\Contracts;

use Laravel\Passport\Client;
use Laravel\Passport\Contracts\AuthorizationViewResponse;

class CustomAuthViewResponse implements AuthorizationViewResponse
{
    private static string $authToken;
    private static $scopes;
    private static $user;

    public function withParameters(array $parameters = [])
    {
        self::$authToken = $parameters['authToken'];
        self::$scopes = $parameters['scopes'];
        self::$user = $parameters['user'];

        return new CustomAuthViewResponse();
    }

    public function toResponse($request)
    {
        $client = Client::query()->findOrFail($request->client_id);

//        dd($request->all(), $client);

        $data = [
            'client' => $client,
            'scopes' => self::$scopes,
            'user' => self::$user,
            'state' => $request->state,
            'authToken' => self::$authToken,
            'client_id' => $request->client_id,
        ];

        return response()->view('passport.auth', $data);
    }
}
