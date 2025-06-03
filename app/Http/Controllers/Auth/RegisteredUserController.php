<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'secondName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'organization' => ['required', 'string', 'max:255'],
            'userRole' => ['required', 'string', 'max:255', 'in:admin,emp'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'second_name' => $request->secondName,
            'last_name' => $request->lastName,
            'organization' => $request->organization,
            'user_role' => $request->userRole
        ]);

        event(new Registered($user));

        Auth::login($user);

        //return redirect(route('dashboard', absolute: false));
        return redirect(route('profile.edit', absolute: false));
    }
}
