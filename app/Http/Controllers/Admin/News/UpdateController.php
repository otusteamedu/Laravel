<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateNewsRequest;
use App\Services\Category\Repositories\CategoryRepositoryInterface;
use App\Services\Category\Results\Fetcher as CategoryFetcher;
use App\Services\User\Results\Fetcher as UserFetcher;
use App\Services\News\Commands\CommandDTO;
use App\Services\News\Exceptions\NewsNotFoundException;
use App\Services\News\Handlers\EditHandler;
use App\Services\News\Handlers\UpdateHandler;
use App\Services\News\Results\NewsDTO;
use App\Services\User\Repositories\UserRepositoryInterface;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\View\View;

class UpdateController extends Controller
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository, private UserRepositoryInterface $userRepository, private CategoryFetcher $categoryFetcher, private UserFetcher $userFetcher)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return View
     */
    public function edit(EditHandler $editNewsUseCase, AuthManager $authManager, string $newsId): View
    {
        Gate::authorize('news.update', $newsId);

        try {
            $news = $editNewsUseCase((int)$newsId);

            $categories = $this->categoryFetcher->fetch($this->categoryRepository->fetchAll())->results;

            $isAdmin = $authManager->user()->hasRole('admin');

            $users = $isAdmin ? $this->userFetcher->fetch($this->userRepository->fetchAll())->results : [];


        } catch (NewsNotFoundException) {
            throw new NotFoundHttpException('News not found');
        }

        return view('admin.news.edit', compact('news', 'categories', 'users', 'isAdmin'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, UpdateHandler $updateNewsUseCase, AuthManager $authManager, string $newsId): RedirectResponse
    {
        Gate::authorize('news.update', $newsId);

        $request->validated();

        $isAdmin = $authManager->user()->hasRole('admin');

        /** @var  NewsDTO */
        $newsDTO = $updateNewsUseCase(new CommandDTO($request->get('title'), $request->get('content'), $request->get('user_id'), $request->get('category_id'), $request->get('published_at'), $request->get('is_draft', false), (int)$newsId), $isAdmin);

        return redirect()->route('admin.news.show', $newsDTO->id);
    }
}
