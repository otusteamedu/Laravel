<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Priority;
use App\Models\User;
use App\Services\Commands\UpdateTask\Command;
use App\Services\Commands\UpdateTask\Handler;
use App\Services\Queries\FetchTaskById\Query;
use App\Services\Queries\FetchTaskById\Fetcher;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateController extends Controller
{
    /**
     * Показать форму редактирования задачи
     */
    public function edit(Fetcher $fetcher, string $taskId): View
    {
        try {
            $query = new Query((int)$taskId);
            $task = $fetcher->fetch($query);
        } catch (\Exception) {
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
    public function update(UpdateTaskRequest $request, Handler $handler, string $taskId)
    {
        $request->validated();

        try {
            $command = new Command(
                id: (int)$taskId,
                title: $request->get('title'),
                description: $request->get('description', ''),
                executorId: (int)$request->get('executor_id'),
                categoryId: (int)$request->get('category_id'),
                priorityId: (int)$request->get('priority_id'),
                status: $request->get('status'),
                dueDate: $request->get('due_date')
            );

            $task = $handler->handle($command);
        } catch (\Exception) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        return redirect()->route('admin.tasks.index')
            ->with('success', "Задача \"{$task->title}\" успешно обновлена");
    }
}
