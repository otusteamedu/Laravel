<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Services\Commands\DeleteCategory\Command;
use App\Services\Commands\DeleteCategory\Handler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DestroyController extends Controller
{
    /**
     * Удалить категорию
     */
    public function destroy(Handler $handler, string $categoryId)
    {
        try {
            $command = new Command((int)$categoryId);
            $handler->handle($command);
        } catch (\Exception) {
            throw new NotFoundHttpException('Категория не найдена');
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно удалена');
    }
}
