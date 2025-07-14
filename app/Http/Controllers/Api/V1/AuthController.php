<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Services\JwtAuth\UseCases\Commands\Login\Handler as LoginHandler;
use App\Services\JwtAuth\UseCases\Commands\Login\Command as LoginCommand;
use App\Services\JwtAuth\UseCases\Commands\Logout\Handler as LogoutHandler;
use App\Services\JwtAuth\UseCases\Commands\Logout\Command as LogoutCommand;
use App\Services\JwtAuth\UseCases\Commands\Refresh\Handler as RefreshHandler;
use App\Services\JwtAuth\UseCases\Commands\Refresh\Command as RefreshCommand;
use Exception;

class AuthController extends Controller
{

    public function __construct(
        private LogoutHandler $logoutHandler,
        private AuthManager $authManager,
    )
    {
    }

    public function login(LoginHandler $loginHandler, Request $request): JsonResponse
    {
        $validated = $request->validate([
                                            'email' => 'required|email',
                                            'password' => 'required|string',
                                        ]);

        try {

            $result = $loginHandler->handle(new LoginCommand($validated['email'], $validated['password'], config('jwt.refresh_ttl')));

            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }


    public function refresh(RefreshHandler $refreshHandler, Request $request): JsonResponse
    {
        $validated = $request->validate(
            [
                'refresh_token' => 'required|string',
            ]
        );

        try {
            $result = $refreshHandler->handle(new RefreshCommand($validated['refresh_token'], config('jwt.refresh_ttl')));

            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     *
     * @param string $refreshToken
     *
     * @return void
     */
    public function revoke(string $refreshToken): void
    {
        $this->logoutHandler->handle(new LogoutCommand($refreshToken));
    }


    public function logout(Request $request): JsonResponse
    {
        $validated = $request->validate([
                                            'refresh_token' => 'required|string',
                                        ]);

        $this->logoutHandler->handle(new LogoutCommand($validated['refresh_token']));

        $this->authManager->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
