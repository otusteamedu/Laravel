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

class VKController extends Controller
{
    public function redirect(Request $request)
    {
        return Socialite::driver('vkid')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $vkUser = Socialite::driver('vkid')->user();

            $command = new Command(
                $vkUser->id,
                'vkid',
                mb_strtolower($vkUser->email),
                $vkUser->name,
            );

            $handler = new Handler(
                new UserRepository,
                new UserSocialeteRepository,
            );

            $handler->handle($command);

            return redirect()->intended(route('todo.list', absolute: false));
        } catch (AuthorizationException) {
            abort(403);
        } catch (Exception) {
            abort(419);
        }
    }
}
