<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\Category\Commands\CommandDTO;
use App\Services\Category\Exceptions\CategoryNotFoundException;
use App\Services\Category\Handlers\EditHandler;
use App\Services\Category\Handlers\UpdateHandler;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\View\View;

class UpdateController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return View
     */
    public function edit(EditHandler $editCategoryUseCase, string $categoryId): View
    {

        try {
            $category = $editCategoryUseCase((int)$categoryId);
        } catch (CategoryNotFoundException) {
            throw new NotFoundHttpException('Category not found');
        }


        return view('admin.categories.edit', compact('category'));// todo return dto
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, UpdateHandler $updateCategoryUseCase, string $categoryId): RedirectResponse
    {
        $request->validated();

        /** @var  $postDTO */
        $postDTO = $updateCategoryUseCase(new CommandDTO($request->get('name'), $request->get('sort'), (int)$categoryId));

        return redirect()->route('admin.categories.show', $postDTO->id);
    }
}
