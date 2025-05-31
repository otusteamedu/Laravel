<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Priority;
use App\Models\User;
use App\Services\Tasks\Commands\CommandDTO;
use App\Services\Tasks\Exceptions\TaskNotFoundException;
use App\Services\Tasks\Handlers\EditHandler;
use App\Services\Tasks\Handlers\UpdateHandler;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateController extends Controller
{
    /**
     * Показать форму редактирования задачи
     */
    public function edit(EditHandler $handler, string $taskId): View
    {
        try {
            $task = $handler((int)$taskId);
        } catch (TaskNotFoundException) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        $users = User::all();
        $categories = Category::all();
        $priorities = Priority::all();

        return view('admin.tasks.edit', compact('task', 'users', 'categories', 'priorities'));
    }
    
    /**
     * Обновить данные задачи
     */
    public function update(UpdateTaskRequest $request, UpdateHandler $handler, string $taskId)
    {
        $request->validated();

        try {
            $task = $handler(new CommandDTO(
                title: $request->get('title'),
                description: $request->get('description', ''),
                executor_id: (int)$request->get('executor_id'),
                category_id: (int)$request->get('category_id'),
                priority_id: (int)$request->get('priority_id'),
                due_date: $request->get('due_date'),
                id: (int)$taskId
            ));
        } catch (TaskNotFoundException) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        return redirect()->route('admin.tasks.index')
            ->with('success', "Задача \"{$task->title}\" успешно обновлена");
    }
} 