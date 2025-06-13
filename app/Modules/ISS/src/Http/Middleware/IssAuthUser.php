<?php

namespace App\Modules\ISS\src\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\ISS\src\Services\issUser\IssUser;
use App\Modules\ISS\src\Services\issUser\fetchIssUserWebToken\FetchIssUserWebToken;
use App\Modules\ISS\src\Services\issUser\fetchIssUserWebToken\InputDTO as fetchTokenDTO;

/**
 * Посредник ограничивает вход в группу маршрутов пользователя ИОС
 */

class IssAuthUser
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

        $this->approvedToken = ($fetchIssUserWebToken
            ->fetchIssUserWebToken(new fetchTokenDTO(issUserId: $this->issUser->issUserId)))->issUserWebToken;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //если пользователь не авторизовался в ИОС то запрещаем доступ
        if (!isset($this->issUser->issUserId) ||
            $this->issUser->webToken != $this->approvedToken
        ) {
            abort(403, __('iss::issMiddleware.accessUserDenied'));
        }

        //если пользователь не админ, то запрещаем ему просмотр страниц других пользователей
        if ($this->issUser->issUserId != $request->issUserId && $this->issUser->issUserRole != config('iss.ROLE_ADMIN')) {
            abort(403, 'iss::issMiddleware.accessAnotherUserDenied');
        }


        $response = $next($request);

        return $response;
    }
}
