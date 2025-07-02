<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateNewsRequest;
use App\Services\Exceptions\News\NewsNotFoundException;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\Commands\UpdateNews\Command;
use App\Services\Commands\UpdateNews\Handler as UpdateHandler;
use App\Services\Queries\FetchNewsById\Query as NewsQuery;
use App\Services\Queries\FetchNewsById\Fetcher as NewsFetcher;
use App\Services\Queries\FetchAllCategories\Fetcher as CategoriesFetcher;
use App\Services\Queries\FetchAllUsers\Fetcher as UsersFetcher;

class UpdateController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return View
     */
    public function edit(NewsFetcher $newsFetcher, AuthManager $authManager, CategoriesFetcher $categoriesFetcher, UsersFetcher $usersFetcher, string $newsId): View
    {
        Gate::authorize('news.update', $newsId);

        try {
            $query = new NewsQuery((int)$newsId);
            $news = $newsFetcher->fetch($query);

            $isAdmin = $authManager->user()->hasRole('admin');

            $categories = $categoriesFetcher->fetch()->results;
            $users = $isAdmin ? $usersFetcher->fetch()->results : [];

            return view('admin.news.edit', compact('news', 'categories', 'users', 'isAdmin'));

        } catch (NewsNotFoundException) {
            throw new NotFoundHttpException('Новость не найдена');
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, UpdateHandler $updateNewsUseCase, AuthManager $authManager, string $newsId): RedirectResponse
    {
        Gate::authorize('news.update', $newsId);

        $request->validated();

        try {
            $isAdmin = $authManager->user()->hasRole('admin');

            $command = new Command(
                id: (int)$newsId,
                title: $request->get('title'),
                content: $request->get('content'),
                userId: $request->get('user_id'),
                categoryId: $request->get('category_id'),
                publishedAt: $request->get('published_at'),
                isDraft: $request->get('is_draft', false),
            );

            $news = $updateNewsUseCase->handle($command, $isAdmin);

        } catch (NewsNotFoundException) {
            throw new NotFoundHttpException('Новость не найдена');
        }

        return redirect()->route('admin.news.show', $news->id);
    }
}
