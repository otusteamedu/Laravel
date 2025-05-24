<?php

namespace App\Http\Controllers\Auth;

use App\Infrastructure\Eloquent\Repositories\UserRepository;
use App\Infrastructure\Eloquent\Repositories\UserSocialeteRepository;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Access\AuthorizationException;
use App\Services\Commands\Auth\Socialete\AuthorizeCommand\Command;
use App\Services\Commands\Auth\Socialete\AuthorizeCommand\Handler;

class YandexController extends Controller
{
    public function redirect(Request $request)
    {
        return Socialite::driver('yandex')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $yandexUser = Socialite::driver('yandex')->user();

            $command = new Command(
                $yandexUser->id,
                'yandex',
                mb_strtolower($yandexUser->email),
                $yandexUser->user['real_name'] ?? $yandexUser->user['display_name']
            );

            $handler = new Handler(
                new UserRepository,
                new UserSocialeteRepository,
            );

            $handler->handle($command);

            $request->session()->regenerate();

            return redirect()->intended(route('todo.list', absolute: false));
        } catch (AuthorizationException) {
            abort(403);
        } catch (Exception) {
            abort(419);
        }
    }
}
