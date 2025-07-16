<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Access\AuthorizationException;
use App\Application\UseCases\Commands\Auth\Socialite\AuthorizeCommand\Command;
use App\Application\UseCases\Commands\Auth\Socialite\AuthorizeCommand\Handler;

class YandexController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('yandex')->redirect();
    }

    public function callback(Request $request, Handler $handler)
    {
        try {
            $yandexUser = Socialite::driver('yandex')->user();

            $handler->handle(
                new Command(
                    $yandexUser->id,
                    'yandex',
                    mb_strtolower($yandexUser->email),
                    $yandexUser->user['real_name'] ?? $yandexUser->user['display_name']
                )
            );

            $request->session()->regenerate();

            return redirect()->intended(route(name: 'projects.index', absolute: false));
        } catch (AuthorizationException) {
            abort(403);
        } catch (Exception) {
            abort(419);
        }
    }
}
