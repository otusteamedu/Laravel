<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Services\Exceptions\Users\UserEmailAlreadyExistsException;
use App\Services\Exceptions\Users\UserNotFoundException;
use App\Services\Exceptions\Users\UserSaveException;
use App\Services\UseCases\Commands\UpdateUser\Command;
use App\Services\UseCases\Commands\UpdateUser\Handler;
use App\Services\UseCases\Queries\FetchUserById\Fetcher;
use App\Services\UseCases\Queries\FetchUserById\Query;
use Exception;
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
        } catch (UserNotFoundException) {
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

            $handler->handle($command);

            return redirect()->route('admin.users.index')
                             ->with('success', "Пользователь '{$request->get('name')}' успешно создан");

        } catch (UserEmailAlreadyExistsException $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', $e->getMessage());

        } catch (UserSaveException $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', $e->getMessage());

        } catch (Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Произошла непредвиденная ошибка при создании пользователя. Попробуйте позже.');
        }
    }
}
