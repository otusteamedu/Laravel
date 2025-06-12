<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

/**
 * Пишем в логи все посещения страниц нашего сайта и точное время посещения
 */
class Logging
{
    public function handle($request, Closure $next)
    {
        Log::channel('urls')->info($request->path().'  '.now());

        return $next($request);
    }
}
