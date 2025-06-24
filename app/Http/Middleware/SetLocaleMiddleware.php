<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SetLocaleMiddleware implements  MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $lang = $request->getQueryParams()['lang'] ?? null;
        $supported = config('locale.supported');
        $default = config('locale.default');

        $locale = $lang ?? $default;

        if (!in_array($locale, $supported)) {
            $locale = $default;
        }

        App::setLocale($locale);

        return $handler->handle($request);
    }
}
