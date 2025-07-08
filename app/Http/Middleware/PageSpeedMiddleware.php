<?php

namespace App\Http\Middleware;

use Closure;
use Debugbar;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PageSpeedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Начало измерения всего запроса
        Debugbar::startMeasure('total_request', 'Скорость загрузки');

        // Выполняем запрос
        $response = $next($request);

        // Завершение измерения всего запроса
        Debugbar::stopMeasure('total_request');
        return $response;
    }
}
