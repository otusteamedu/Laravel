<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\Commands\UpdateUser\Command;
use App\Services\Commands\UpdateUser\Handler;
use App\Services\Queries\FetchUserById\Query;
use App\Services\Queries\FetchUserById\Fetcher;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateController extends Controller
{
    /**
     * Показать форму редактирования пользователя
     */
    public function edit(Fetcher $fetcher, string $userId): View
    {
        try {
            $query = new Query((int)$userId);
            $user = $fetcher->fetch($query);
        } catch (\Exception) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Обновить данные пользователя
     */
    public function update(UpdateUserRequest $request, Handler $handler, string $userId)
    {
        $request->validated();

        try {
            $command = new Command(
                id: (int)$userId,
                name: $request->get('name'),
                email: $request->get('email'),
                password: $request->get('password')
            );

            $user = $handler->handle($command);
        } catch (\Exception) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Пользователь \"{$user->name}\" успешно обновлен");
    }
}
