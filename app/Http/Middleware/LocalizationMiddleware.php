<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = locale_accept_from_http($request->header('Accept-Language'));

        $available_locales = config()->get('locale.available_locales');

        if (empty($available_locales[$locale])) {
            $locale = env('APP_LOCALE');
        }

        $locale = $request->session()->get('locale') ?? $locale;
        App::setLocale($locale);

        View::share('locales', $available_locales);

        return $next($request);
    }
}
