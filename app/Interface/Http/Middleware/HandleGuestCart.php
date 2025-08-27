<?php

namespace App\Interface\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class HandleGuestCart
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Если пользователь не авторизован и нет guest token в запросе
        if (!auth('jwt')->check() && !$request->header('X-Guest-Token')) {
            // Генерируем новый guest token для ответа
            $guestToken = Str::random(32);
            $response->headers->set('X-Guest-Token', $guestToken);
        }

        return $response;
    }
}
