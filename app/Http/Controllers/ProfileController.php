<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\Repositories\DTOs\UserProfileDTO;
use App\Services\Repositories\UserRepositoryInterface;

class ProfileController extends Controller
{
    public function __construct(
        private AuthManager $auth,
        private UserRepositoryInterface $userRepository,
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
            userId: $user->id,
            biography: $validated['profile']['biography'] ?? '',
            telegram_id: $validated['profile']['telegram_id'] ?? null,
        );

        $this->userRepository->saveProfile($userProfileDTO);

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
