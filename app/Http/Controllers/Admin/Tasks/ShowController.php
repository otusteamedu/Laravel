<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Tasks\Handlers\ShowHandler;
use App\Services\Tasks\Exceptions\TaskNotFoundException;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowController extends Controller
{
    /**
     * Показать детали задачи
     */
    public function __invoke(ShowHandler $handler, string $taskId): View
    {
        try {
            $task = $handler((int)$taskId);
        } catch (TaskNotFoundException) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        return view('admin.tasks.show', compact('task'));
    }
} 