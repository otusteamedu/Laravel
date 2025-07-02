<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FallbackController extends Controller
{
    public function __invoke(Request $request, Config $config, Router $router)
    {
        $path = $request->path();
        $supportedLocales = $config::get('app.supported_locales');

        // Если путь уже содержит локаль — не редиректим, просто 404
        if (preg_match('#^(' . implode('|', $supportedLocales) . ')(/|$)#', $path)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        // Определяем локаль из браузера
        $locale = detectLocaleFromBrowser($supportedLocales, $config::get('app.fallback_locale'));

        // Сконструируем путь с локалью
        $localizedPath = '/' . $locale . '/' . ltrim($path, '/');

        // Пробуем сматчить путь с роутами
        $testRequest = Request::create($localizedPath, request()->method());

        try {
            $matchedRoute = $router->getRoutes()->match($testRequest);

            // Проверим, не fallback ли это — если да, считаем что маршрут не существует
            if ($matchedRoute->uri() === '{fallbackPlaceholder}') {
                abort(Response::HTTP_NOT_FOUND);
            }

        } catch (NotFoundHttpException) {
            abort(Response::HTTP_NOT_FOUND);
        }

        // Только если маршрут существует — редиректим
        return Redirect::to($localizedPath);
    }
}
