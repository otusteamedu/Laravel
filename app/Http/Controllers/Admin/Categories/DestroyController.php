<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Application\Contracts\CacheInterface;
use App\Application\UseCases\Category\Commands\DeleteCategory\Command;
use App\Application\UseCases\Category\Commands\DeleteCategory\Handler;
use App\Domain\News\Exceptions\CategoryNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
