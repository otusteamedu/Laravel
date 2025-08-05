<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Tasks\TaskDomainService;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowController extends Controller
{
    /**
     * Показать детали задачи
     */
    public function show(TaskDomainService $taskService, string $taskId): View
    {
        $task = $taskService->getTaskById((int)$taskId);
        
        if (!$task) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        return view('admin.tasks.show', compact('task'));
    }
} 