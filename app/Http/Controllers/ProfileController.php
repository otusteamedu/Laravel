<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\Repositories\DTOs\UserProfileDTO;
use App\Services\Repositories\UserProfileRepository;
use App\Services\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private AuthManager $auth,
        private UserProfileRepository $userProfileRepository,
    ) {
        //
    }
    /**
     * Display the user's profile form.
     */
    public function edit(): View
    {
        return view('profile.profile', [
            'user' => $this->auth->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $this->auth->user();

        $validated = $request->validated();

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $userProfileDTO = new UserProfileDTO(
            user_id: $user->id,
            biography: $validated['biography']
        );

        $this->userProfileRepository->save($userProfileDTO);

        return Redirect::route('profile.edit')->with('success', 'Профиль обновлен');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
