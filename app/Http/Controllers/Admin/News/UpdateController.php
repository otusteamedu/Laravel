<?php

namespace App\Http\Controllers\Admin\News;

use App\Application\UseCases\Category\Queries\FetchAllCategories\Fetcher as CategoriesFetcher;
use App\Application\UseCases\News\Commands\UpdateNews\Command;
use App\Application\UseCases\News\Commands\UpdateNews\Handler as UpdateHandler;
use App\Application\UseCases\News\Queries\FetchNewsById\Fetcher as NewsFetcher;
use App\Application\UseCases\News\Queries\FetchNewsById\Query as NewsQuery;
use App\Application\UseCases\User\Queries\FetchAllUsers\Fetcher as UsersFetcher;
use App\Domain\News\Exceptions\NewsNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateNewsRequest;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

            $categories = $categoriesFetcher->fetch()->items;
            $users = $isAdmin ? $usersFetcher->fetch()->items : [];

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

            $publishedAt = $request->get('published_at')
                ? new \DateTimeImmutable($request->get('published_at'))
                : null;

            $command = new Command(
                id: (int)$newsId,
                title: $request->get('title'),
                content: $request->get('content'),
                authorId: $request->get('author_id'),
                categoryId: $request->get('category_id'),
                publishedAt: $publishedAt,
                isDraft: $request->get('is_draft', false),
            );

            $news = $updateNewsUseCase->handle($command, $isAdmin);

        } catch (NewsNotFoundException) {
            throw new NotFoundHttpException('Новость не найдена');
        }

        return redirect()->route('admin.news.show', $news->id);
    }
}
