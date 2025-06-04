<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\Commands\DeleteUser\Command;
use App\Services\Commands\DeleteUser\Handler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DestroyController extends Controller
{
    /**
     * Удалить пользователя
     */
    public function destroy(Handler $handler, string $userId)
    {
        try {
            $command = new Command((int)$userId);
            $handler->handle($command);
        } catch (\Exception) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Пользователь успешно удален");
    }
} 