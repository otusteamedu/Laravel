<?php

namespace App\Http\Controllers\Admin\Users;

use App\Application\UseCases\User\Commands\UpdateUser\Command;
use App\Application\UseCases\User\Commands\UpdateUser\Handler;
use App\Application\UseCases\User\Queries\FetchUserById\Fetcher;
use App\Application\UseCases\User\Queries\FetchUserById\Query;
use App\Domain\User\Exceptions\UserEmailAlreadyExistsException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Exceptions\UserSaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
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
