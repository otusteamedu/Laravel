<?php

namespace App\Http\Controllers\Public\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Commands\DeleteTask\Command as DeleteTaskCommand;
use App\Services\Commands\DeleteTask\Handler as DeleteTaskHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteController extends Controller
{
    /**
     * Удалить задачу
     */
    public function destroy(DeleteTaskHandler $handler, string $taskId)
    {
        // Получаем модель Task для авторизации
        $taskModel = \App\Models\Task::findOrFail($taskId);
        $this->authorize('delete', $taskModel);
        
        try {
            $command = new DeleteTaskCommand((int)$taskId);
            $handler->handle($command);
        } catch (\Exception) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Задача успешно удалена');
    }
} 