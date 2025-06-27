<?php

namespace My\PackageWithPackages\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class PackHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $header): Response
    {
        //запрос не обрабатываем
        $response = $next($request);

        $headerArr = explode('=', $header);
        $headerName = $headerArr[0];
        $headerContent = $headerArr[1];

        $response->header($headerName, $headerContent);
        return $response;
    }
}
