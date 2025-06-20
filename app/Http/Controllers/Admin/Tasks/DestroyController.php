<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Commands\DeleteTask\Command;
use App\Services\Commands\DeleteTask\Handler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DestroyController extends Controller
{
    /**
     * Удалить задачу
     */
    public function destroy(Handler $handler, string $taskId)
    {
        try {
            $command = new Command((int)$taskId);
            $handler->handle($command);
        } catch (\Exception) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Задача успешно удалена');
    }
} 