<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Пишем в логи все посещения страниц нашего сайта и точное время посещения
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->segment(1) !== 'ru' && $request->segment(1) !== 'en') {
            abort(Response::HTTP_FORBIDDEN);
        }

        app()->setLocale($request->segment(1));

        return $next($request);
    }
}
