<?php

namespace App\Http\Controllers\Public\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Commands\CreateTask\Command as CreateTaskCommand;
use App\Services\Commands\CreateTask\Handler as CreateTaskHandler;
use App\Http\Requests\CreateTaskRequest;
use App\Models\Priority;
use App\Models\User;
use App\Models\Category;
use Illuminate\View\View;

class CreateController extends Controller
{
    /**
     * Показать форму создания задачи
     */
    public function create(): View
    {
        $this->authorize('create', \App\Models\Task::class);
        
        $users = User::all();
        $categories = Category::all();
        $priorities = Priority::all();

        return view('tasks.create', compact('users', 'categories', 'priorities'));
    }

    /**
     * Сохранить новую задачу
     */
    public function store(CreateTaskRequest $request, CreateTaskHandler $handler)
    {
        $this->authorize('create', \App\Models\Task::class);
        
        $request->validated();

        $command = new CreateTaskCommand(
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

        return redirect()->route('tasks.index')
            ->with('success', 'Задача успешно создана');
    }
} 