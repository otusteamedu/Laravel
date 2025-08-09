<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ForceLogin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  Closure(Request): Response|RedirectResponse  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Fetch the user with ID 1
        $user = User::query()->find(1);

        if ($user) {
            // Log in the user
            Auth::login($user);
        }

        return $next($request);
    }
}