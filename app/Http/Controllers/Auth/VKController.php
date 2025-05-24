<?php

namespace App\Http\Controllers\Auth;

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

    public function callback(Request $request, Handler $loginUseCase)
    {
        try {
            $vkUser = Socialite::driver('vkid')->user();

            $loginUseCase(
                new Command(
                    $vkUser->id,
                    'vkid',
                    mb_strtolower($vkUser->email),
                    $vkUser->name,
                )
            );

            $request->session()->regenerate();

            return redirect()->intended(route('todo.list', absolute: false));
        } catch (AuthorizationException) {
            abort(403);
        } catch (Exception) {
            abort(419);
        }
    }
}
