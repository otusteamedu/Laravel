<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
class CheckLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $allowedLocales = ['en', 'ru'];
        $locale = $request->segment(1); // Предполагается, что локаль в URL на первом месте, например, /en/profile

        if (in_array($locale, $allowedLocales)) {
            App::setLocale($locale);
        } else {
            //  Перенаправление или обработка ошибки, если локаль не поддерживается
            // Например, перенаправление на страницу с выбором локали или установка локали по умолчанию
            // $request->session()->flash('error', 'Неподдерживаемая локаль');
            // return redirect('/');
            App::setLocale('en'); // Установка локали по умолчанию
        }

        return $next($request);
    }
}
