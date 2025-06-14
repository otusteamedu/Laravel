<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * PSR-7, PSR-15
 * jshannon63/laravel-psr15-middleware - не устанавливается на Laravel 12 php 8.4
 * softonic/laravel-psr15-bridge - проблема с установкой на Laravel 12 из конфлика зависимостей Laravel (illuminate/http)
 */
class SetLocalePSRMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        $segments = explode('/', trim($path, '/'));
        $locale = $segments[0] ?? null;

        if (in_array($locale, config('app.supported_locales'))) {
            app()->setLocale($locale);
        }

        return $handler->handle($request);
    }
}
