<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Priority;
use App\Models\User;
use App\Services\Commands\CreateTask\Command;
use App\Services\Commands\CreateTask\Handler;
use App\Http\Requests\CreateTaskRequest;

class CreateController extends Controller
{
    /**
     * Показать форму для создания задачи
     */
    public function create()
    {
        $users = User::all();
        $categories = Category::all();
        $priorities = Priority::all();

        return view('admin.tasks.create', compact('users', 'categories', 'priorities'));
    }

    /**
     * Сохранить новую задачу
     */
    public function store(CreateTaskRequest $request, Handler $handler)
    {
        $request->validated();

        $command = new Command(
            title: $request->get('title'),
            description: $request->get('description', ''),
            executorId: (int)$request->get('executor_id'),
            categoryId: (int)$request->get('category_id'),
            priorityId: (int)$request->get('priority_id'),
            creatorId: auth()->id(),
            status: $request->get('status', 'новая'),
            dueDate: $request->get('due_date')
        );

        $handler->handle($command);

        return redirect()->route('admin.tasks.index')
            ->with('success', "Задача успешно создана");
    }
}
