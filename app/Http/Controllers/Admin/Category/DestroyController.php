<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Services\Category\Exceptions\CategoryNotFoundException;
use App\Services\Category\Handlers\DestroyHandler;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DestroyController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function __invoke(DestroyHandler $destroyCategoryUseCase, int $categoryId): RedirectResponse
    {

        try {
            $destroyCategoryUseCase($categoryId);
        } catch (CategoryNotFoundException) {
            throw new NotFoundHttpException('Category not found');
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category has been deleted');
    }
}
