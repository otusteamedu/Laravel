<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Priority;
use App\Models\User;
use App\Services\Tasks\Commands\CommandDTO;
use App\Services\Tasks\Handlers\CreateHandler;
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
    public function store(CreateTaskRequest $request, CreateHandler $handler)
    {
        $request->validated();

        $result = $handler(new CommandDTO(
            title: $request->get('title'),
            description: $request->get('description', ''),
            executor_id: (int)$request->get('executor_id'),
            category_id: (int)$request->get('category_id'),
            priority_id: (int)$request->get('priority_id'),
            due_date: $request->get('due_date')
        ));

        return redirect()->route('admin.tasks.index')
            ->with('success', "Задача успешно создана");
    }
} 