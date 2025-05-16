<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Auth\VKIDService;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Access\AuthorizationException;

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

            (new VKIDService)->authorize($vkUser);
            $request->session()->regenerate();

            return redirect()->intended(route('user.todos', absolute: false));
        } catch (AuthorizationException $e) {
            abort(403);
        } catch (Exception $e) {
            dd($e->getMessage());
            abort(419);
        }
    }
}
