<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Auth\YandexService;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Access\AuthorizationException;

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
            (new YandexService)->authorize($yandexUser);

            $request->session()->regenerate();

            return redirect()->intended(route('todo.list', absolute: false));
        } catch (AuthorizationException $e) {
            abort(403);
        } catch (Exception $e) {
            abort(419);
        }
    }
}
