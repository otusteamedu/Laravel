<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Пишем в логи все посещения страниц нашего сайта и точное время посещения
 */
class SetLocale
{
    public function handle($request, Closure $next)
    {
        app()->setLocale($request->segment(1));

        return $next($request);
    }
}
