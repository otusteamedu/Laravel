<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Infrastructure\Cache\CacheInterface;
use App\Services\Commands\UpdateCategory\Command;
use App\Services\Commands\UpdateCategory\Handler;
use App\Services\Queries\FetchCategoryById\Query;
use App\Services\Queries\FetchCategoryById\Fetcher;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\Exceptions\Categories\CategoryNotFoundException;
use Illuminate\Support\Facades\Gate;
use App\Services\Exceptions\Categories\CategoryAlreadyExistsException;

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
                sort: $request->get('sort'),

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
