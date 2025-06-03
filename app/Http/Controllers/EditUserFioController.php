<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Services\userService\editUser\EditUser;
use App\Services\userService\editUser\InputDTO  as editUserDTO;

class EditUserFioController
{
    /**
     * Форма редактироваания ФИО сотрудника основного приложения
     * @param int $userForEditId код редактируемого сотрудника
     * @return View
     */
    public function edit(int $userForEditId): View
    {
        return view('editUserFio', ['userForEditId' => $userForEditId, 'msg' => session('notUpdated')]);
    }

    /**
     * Контроллер для редактирования ФИО сотрудника из основного приложения
     * @param Request $request объект запроса
     * @param int $userForEditId код редактируемого пользователя
     * @param EditUser $editUser сервис редактирования пользователя
     * @return
     */
    public function update(
        Request $request,
        EditUser $editUser,
        int $userForEditId
    )
    {
        //валидация
        $validationRules = [
            'name' => 'required|min:2|string',
            'lastName' => 'required|min:2|string',
            'secondName' => 'required|min:2|string',
        ];

        $errorMessages = [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be greater than 2 characters',
            'name.string' => 'Name must be string',

            'lastName.required' => 'Name is required',
            'lastName.min' => 'Name must be greater than 2 characters',
            'lastName.string' => 'Name must be string',

            'secondName.required' => 'Name is required',
            'secondName.min' => 'Name must be greater than 2 characters',
            'secondName.string' => 'Name must be string',
        ];

        $fioValidator = Validator::make(
            $request->only(['name', 'lastName', 'secondName']),
            $validationRules,
            $errorMessages
        );

        $validated = $fioValidator->validate();

        //обращаюсь к сервису обновления пользователя
        $result = $editUser(
            new editUserDTO(
                userId: $userForEditId,
                lastName: $validated['name'],
                name: $validated['lastName'],
                secondName: $validated['secondName']
            )
        );

        if ($result) {
            return redirect()->route('profile.edit');
        } else {
            return redirect()->back()->with('notUpdated', 'User not updated!');
        }
    }
}
