<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Services\Commands\CreateCategory\Command;
use App\Services\Commands\CreateCategory\Handler;
use App\Services\Exceptions\Categories\CategoryAlreadyExistsException;
use App\Services\Exceptions\Categories\CategorySaveException;
use Exception;

class CreateController extends Controller
{
    /**
     * Показать форму создания категории
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Создать новую категорию
     */
    public function store(CreateCategoryRequest $request, Handler $handler)
    {
        try {
            $request->validated();

            $command = new Command(
                name: $request->get('name'),
                color: $request->get('color'),
                description: $request->get('description')
            );

            $handler->handle($command);

            return redirect()->route('admin.categories.index')
                ->with('success', "Категория '{$request->get('name')}' успешно создана");

        } catch (CategoryAlreadyExistsException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());

        } catch (CategorySaveException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Произошла непредвиденная ошибка при создании категории. Попробуйте позже.');
        }
    }
}
