<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\Users\Commands\CommandDTO;
use App\Services\Users\Handlers\CreateHandler;
use App\Http\Requests\CreateUserRequest;

class CreateController extends Controller
{

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(CreateUserRequest $request, CreateHandler $handler)
    {
        $request->validated();

        $result = $handler(new CommandDTO(
            name: $request->get('name'),
            email: $request->get('email'),
            password: $request->get('password')
        ));

        return redirect()->route('admin.users.index')
            ->with('success', "Пользователь успешно создан");
    }
}
