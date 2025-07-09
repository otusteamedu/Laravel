<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Application\UseCases\Auth\Commands\Login\Command as LoginCommand;
use App\Application\UseCases\Auth\Commands\Login\Handler as LoginHandler;
use App\Application\UseCases\Auth\Commands\RefreshToken\Command as RefreshTokenCommand;
use App\Application\UseCases\Auth\Commands\RefreshToken\Handler as RefreshTokenHandler;
use App\Application\UseCases\Auth\Commands\RevokeRefreshToken\Command as RevokeRefreshTokenCommand;
use App\Application\UseCases\Auth\Commands\RevokeRefreshToken\Handler as RevokeRefreshTokenHandler;
use App\Application\UseCases\Auth\Commands\Logout\Command as LogoutCommand;
use App\Application\UseCases\Auth\Commands\Logout\Handler as LogoutHandler;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use DomainException;

class AuthController extends Controller
{
    public function __construct(
        private LoginHandler $loginHandler,
        private RefreshTokenHandler $refreshTokenHandler,
        private RevokeRefreshTokenHandler $revokeHandler,
        private LogoutHandler $logoutHandler,
    ) {}

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
                                            'email' => 'required|email',
                                            'password' => 'required|string',
                                        ]);

        try {
            $result = $this->loginHandler->handle(new LoginCommand($validated['email'], $validated['password']));
            return response()->json($result);
        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function refresh(Request $request): JsonResponse
    {
        $validated = $request->validate([
                                            'refresh_token' => 'required|string',
                                        ]);

        try {
            $result = $this->refreshTokenHandler->handle(new RefreshTokenCommand($validated['refresh_token']));
            return response()->json($result);
        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     *
     * @param string $refreshToken
     *
     * @return bool
     */
    public function revoke(string $refreshToken): bool
    {
        return  $this->revokeHandler->handle(new RevokeRefreshTokenCommand($refreshToken));
    }

    public function logout(Request $request): JsonResponse
    {
        $validated = $request->validate([
                                            'refresh_token' => 'required|string',
                                        ]);

        $this->logoutHandler->handle(new LogoutCommand($validated['refresh_token']));

        return response()->json(['message' => 'Successfully logged out']);
    }
}
