<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Infrastructure\Cache\CacheInterface;
use App\Services\Commands\DeleteCategory\Command;
use App\Services\Commands\DeleteCategory\Handler;
use App\Services\Exceptions\Categories\CategoryNotFoundException;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Gate;

class DestroyController extends Controller
{
    public function __construct(
        private CacheInterface $cache,
    )
    {
    }

    /**
     * Удалить категорию
     */
    public function __invoke(Handler $handler, string $categoryId): RedirectResponse
    {
        Gate::authorize('category.delete', $categoryId);

        try {
            $command = new Command((int)$categoryId);
            $handler->handle($command);

            $this->cache->flushTagged('categories');
        } catch (CategoryNotFoundException) {
            throw new NotFoundHttpException('Категория не найдена');
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно удалена');
    }
}
