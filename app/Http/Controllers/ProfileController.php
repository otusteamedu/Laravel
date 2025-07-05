<?php

namespace App\Http\Controllers;

use App\Dto\User\PasswordDto;
use App\Dto\User\ProfileDto;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Services\OrdersService;
use App\Services\UsersService;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\RedirectResponse;
use Hash;

class ProfileController extends Controller
{
    public function __construct(
        private UsersService $service,
        private OrdersService $ordersService
    ) {}

    public function edit(int $userId): View
    {
        try {
            $user = $this->service->getById($userId);
        } catch (UserNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        $data = [
            'userId' => $userId,
            'name' => $user->name,
            'email' => $user->email,
        ];

        return view('cabinet.profile', $data);
    }

    public function update(UpdateRequest $request, int $userId): RedirectResponse
    {
        $data = $request->validated();
        $dto = new ProfileDto(
            $userId, 
            $data['name'], 
            $data['email'],
        );

        try {
            $this->service->updateProfile($dto);
        } catch (UserNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return redirect()->route('profile.edit', ['userId' => $userId])->with('success', 'Профиль успешно обновлен');
    }

    public function updatePassword(PasswordRequest $request, int $userId): RedirectResponse
    {
        $data = $request->validated();
        $dto = new PasswordDto(
            $userId, 
            Hash::make($data['password'])
        );

        try {
            $this->service->updatePassword($dto);
        } catch (UserNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return redirect()->route('profile.edit', ['userId' => $userId])->with('success', 'Пароль успешно обновлен');
    }

    public function history(int $userId): View
    {
        try {
            $user = $this->service->getById($userId);
        } catch (UserNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
        
        $orders = $user->orders;
        return view('cabinet.history', compact('orders'));
    }
}