<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Для заданных страниц элементов блога делаем редирект на страницу раздела
 */
class Redirect
{
    protected static array $arUrls = [
        'blogs/1',
        'blogs/3',
        'blogs/5',
    ];

    public function handle($request, Closure $next)
    {

        if (in_array($request->path(), static::$arUrls)) {
            return redirect('/blogs');
        }

        return $next($request);
    }
}
