<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UseCases\Commands\Auth\Password\Update\Command;
use App\Services\UseCases\Commands\Auth\Password\Update\Handler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request, Handler $handler): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $handler->handle(new Command(
            userId: $request->user()->id,
            password: $validated['password']
        ));

        return back()->with('success', 'Пароль обновлен');
    }
}
