<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Tasks\TaskDomainService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DestroyController extends Controller
{
    /**
     * Удалить задачу
     */
    public function destroy(TaskDomainService $taskService, string $taskId)
    {
        $success = $taskService->deleteTask((int)$taskId);
        
        if (!$success) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Задача успешно удалена');
    }
} 