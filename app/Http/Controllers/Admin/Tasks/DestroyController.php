<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Tasks\Exceptions\TaskNotFoundException;
use App\Services\Tasks\Handlers\DestroyHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DestroyController extends Controller
{
    /**
     * Удалить задачу
     */
    public function __invoke(DestroyHandler $destroyHandler, string $taskId)
    {
        try {
            $destroyHandler((int)$taskId);
        } catch (TaskNotFoundException) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        return redirect()->route('admin.tasks.index')
            ->with('success', "Задача успешно удалена");
    }
} 