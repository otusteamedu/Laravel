<?php

namespace App\Http\Controllers\Admin\Users;

use App\Application\UseCases\User\Commands\CreateUser\Command;
use App\Application\UseCases\User\Commands\CreateUser\Handler;
use App\Domain\User\Exceptions\UserEmailAlreadyExistsException;
use App\Domain\User\Exceptions\UserSaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use Exception;

class CreateController extends Controller
{
    /**
     * Показать форму создания пользователя
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Сохранить нового пользователя
     */
    public function store(CreateUserRequest $request, Handler $handler)
    {
        try {
            $request->validated();

            $command = new Command(
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
