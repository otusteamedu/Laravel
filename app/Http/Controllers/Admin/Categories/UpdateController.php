<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCategoryRequest;
use App\Infrastructure\Cache\CacheInterface;
use App\Services\Exceptions\Categories\CategoryAlreadyExistsException;
use App\Services\Exceptions\Categories\CategoryNotFoundException;
use App\Services\UseCases\Commands\UpdateCategory\Command;
use App\Services\UseCases\Commands\UpdateCategory\Handler;
use App\Services\UseCases\Queries\FetchCategoryById\Fetcher;
use App\Services\UseCases\Queries\FetchCategoryById\Query;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateController extends Controller
{

    public function __construct(
        private CacheInterface $cache,
    )
    {
    }

    /**
     * Показать форму редактирования категории
     */
    public function edit(Fetcher $fetcher, string $categoryId): View
    {
        Gate::authorize('category.update', $categoryId);

        try {
            $query = new Query((int)$categoryId);
            $category = $fetcher->fetch($query);
        } catch (CategoryNotFoundException) {
            throw new NotFoundHttpException('Категория не найдена');
        }

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Обновить данные категории
     */
    public function update(UpdateCategoryRequest $request, Handler $handler, string $categoryId)
    {
        Gate::authorize('category.update', $categoryId);

        $request->validated();

        try {
            $command = new Command(
                id: (int)$categoryId,
                name: $request->get('name'),
                isActive: $request->get('is_active', false),
                sort: $request->get('sort', 1),
            );

            $category = $handler->handle($command);

            $this->cache->flushTagged('categories');

            return redirect()->route('admin.categories.index')
                             ->with('success', "Категория '{$category->name}' успешно обновлена");

        } catch (CategoryAlreadyExistsException $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', $e->getMessage());

        } catch (\Exception) {
            throw new NotFoundHttpException('Категория не найдена');
        }
    }
}
