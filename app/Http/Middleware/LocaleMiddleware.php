<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Response;


class LocaleMiddleware implements MiddlewareInterface
{
    /**
     * Обработка входящего запроса
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->getLocaleFromRequest($request);

        if ($this->isValidLocale($locale)) {
            $this->setApplicationLocale($locale);
        }

        return $next($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Извлекаем локаль из URI
        $uri = $request->getUri();
        $path = trim($uri->getPath(), '/');
        $segments = explode('/', $path);

        $locale = $segments[0] ?? null;

        if ($this->isValidLocale($locale)) {
            $this->setApplicationLocale($locale);
        }

        return $handler->handle($request);
    }

    /**
     * Извлечение локали из Laravel Request
     *
     * @param Request $request
     * @return string|null
     */
    private function getLocaleFromRequest(Request $request): ?string
    {
        $segments = $request->segments();
        return $segments[0] ?? null;
    }

    /**
     * Проверка валидности локали
     *
     * @param string|null $locale
     * @return bool
     */
    private function isValidLocale(?string $locale): bool
    {
        if (!$locale) {
            return false;
        }

        $supportedLocales = array_keys(Config::get('localization.supported', []));
        return in_array($locale, $supportedLocales, true);
    }

    /**
     * Установка локали приложения
     *
     * @param string $locale
     * @return void
     */
    private function setApplicationLocale(string $locale): void
    {
        App::setLocale($locale);

        // Устанавливаем локаль для Carbon (даты)
        if (class_exists('\Carbon\Carbon')) {
            \Carbon\Carbon::setLocale($locale);
        }
    }
}
