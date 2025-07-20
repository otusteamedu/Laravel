<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->query('locale')
            ?? $request->header('Accept-Language')
            ?? $request->cookie('locale')
            ?? config('app.locale');
        $supported = ['en', 'ru'];
        $locale = substr($locale, 0, 2);
        if (in_array($locale, $supported)) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.fallback_locale'));
        }
        return $next($request);
    }
}

