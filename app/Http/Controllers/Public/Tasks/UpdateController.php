<?php

namespace App\Http\Controllers\Public\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Queries\FetchTaskById\Query as FetchTaskByIdQuery;
use App\Services\Queries\FetchTaskById\Fetcher as FetchTaskByIdFetcher;
use App\Services\Commands\UpdateTask\Command as UpdateTaskCommand;
use App\Services\Commands\UpdateTask\Handler as UpdateTaskHandler;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Priority;
use App\Models\User;
use App\Models\Category;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateController extends Controller
{
    /**
     * Показать форму редактирования задачи
     */
    public function edit(FetchTaskByIdFetcher $taskFetcher, string $taskId): View
    {
        // Получаем модель Task для авторизации
        $taskModel = \App\Models\Task::findOrFail($taskId);
        $this->authorize('update', $taskModel);
        
        try {
            $taskQuery = new FetchTaskByIdQuery((int)$taskId);
            $task = $taskFetcher->fetch($taskQuery);
        } catch (\Exception) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        $users = User::all();
        $categories = Category::all();
        $priorities = Priority::all();

        return view('tasks.edit', compact('task', 'users', 'categories', 'priorities'));
    }

    /**
     * Обновить задачу
     */
    public function update(UpdateTaskRequest $request, UpdateTaskHandler $handler, string $taskId)
    {
        // Получаем модель Task для авторизации
        $taskModel = \App\Models\Task::findOrFail($taskId);
        $this->authorize('update', $taskModel);
        
        $request->validated();

        try {
            $command = new UpdateTaskCommand(
                id: (int)$taskId,
                title: $request->get('title'),
                description: $request->get('description', ''),
                executorId: (int)$request->get('executor_id'),
                categoryId: (int)$request->get('category_id'),
                priorityId: (int)$request->get('priority_id'),
                creatorId: auth()->id(),
                status: $request->get('status'),
                dueDate: $request->get('due_date')
            );

            $task = $handler->handle($command);
        } catch (\Exception) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        return redirect()->route('tasks.index')
            ->with('success', "Задача \"{$task->title}\" успешно обновлена");
    }
} 