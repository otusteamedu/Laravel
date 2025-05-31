<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\Users\Commands\CommandDTO;
use App\Services\Users\Exceptions\UserNotFoundException;
use App\Services\Users\Handlers\EditHandler;
use App\Services\Users\Handlers\UpdateHandler;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateController extends Controller
{
    public function edit(EditHandler $handler, string $userId): View
    {
        try {
            $user = $handler((int)$userId);
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, UpdateHandler $handler, string $userId)
    {
        $request->validated();

        try {
            $user = $handler(new CommandDTO(
                name: $request->get('name'),
                email: $request->get('email'),
                password: $request->get('password'),
                id: (int)$userId
            ));
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Пользователь успешно обновлен");
    }
}
