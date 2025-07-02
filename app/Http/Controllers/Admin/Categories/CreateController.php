<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Infrastructure\Cache\CacheInterface;
use App\Services\Exceptions\Categories\CategoryAlreadyExistsException;
use App\Services\Exceptions\Categories\CategorySaveException;
use App\Services\UseCases\Commands\CreateCategory\Command;
use App\Services\UseCases\Commands\CreateCategory\Handler;
use Exception;
use Illuminate\Support\Facades\Gate;


class CreateController extends Controller
{

    public function __construct(
        private CacheInterface $cache,
    )
    {
    }

    /**
     * Показать форму для создания категории
     */
    public function create()
    {
        Gate::authorize('category.create');

        return view('admin.categories.create');
    }

    /**
     * Сохранить новую категорию
     */
    public function store(CreateCategoryRequest $request, Handler $handler)
    {
        Gate::authorize('category.create');

        $request->validated();

        try {
            $command = new Command(
                name: $request->get('name'),
                sort: $request->get('sort'),
                isActive: $request->get('is_active', false),
            );

            $handler->handle($command);

            $this->cache->flushTagged('categories');

            return redirect()->route('admin.categories.index')->with('success', "Категория успешно создана");
        } catch (CategoryAlreadyExistsException $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', $e->getMessage());

        } catch (CategorySaveException $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', $e->getMessage());

        }  catch (Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Произошла непредвиденная ошибка при создании категории. Попробуйте позже.');
        }
    }
}
