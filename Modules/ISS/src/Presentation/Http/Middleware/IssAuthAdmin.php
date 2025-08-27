<?php

namespace ISS\App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use ISS\App\Application\Services\IssUser\IssUser;
use ISS\App\Application\Services\IssUser\FetchIssUserWebToken\FetchIssUserWebToken;
use ISS\App\Application\Services\IssUser\FetchIssUserWebToken\InputDTO as fetchTokenDTO;

/**
 * Посредник ограничивает вход в группу маршрутов интерфейса администратора для модуля ИОС
 */

class IssAuthAdmin
{
    private IssUser|null $issUser;
    private string|null $approvedToken;

    public function __construct(
        FetchIssUserWebToken $fetchIssUserWebToken,
    )
    {
        if (session()->has('issUser')) {
            $this->issUser = session('issUser');
        } else {
            $this->issUser = null;
        }

        $this->approvedToken =
            ($fetchIssUserWebToken(new fetchTokenDTO(issUserId: $this->issUser->issUserId)))->issUserWebToken;

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
    {
        //достаем роль пользователя в ИОС и если она не админ то не пускаем 403
        if (is_null($this->issUser) ||
            $this->issUser->issUserRole != config('iss.ROLE_ADMIN') ||
            $this->issUser->webToken != $this->approvedToken
        ) {
            abort(403, __('iss::issMiddleware.accessAdminDenied'));
        }

        $response = $next($request);

        return $response;
    }
}
