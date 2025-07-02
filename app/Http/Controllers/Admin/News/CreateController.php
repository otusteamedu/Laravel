<?php

namespace App\Http\Controllers\Admin\News;

use App\Events\NewsPublished;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewsRequest;
use App\Services\Exceptions\News\NewsNotFoundException;
use App\Services\UseCases\Commands\CreateNews\Command;
use App\Services\UseCases\Commands\CreateNews\Handler as CreateHandler;
use App\Services\UseCases\Queries\FetchAllCategories\Fetcher as CategoriesFetcher;
use App\Services\UseCases\Queries\FetchAllUsers\Fetcher as UsersFetcher;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(AuthManager $authManager, CategoriesFetcher $categoriesFetcher, UsersFetcher $usersFetcher): View
    {
        $isAdmin = $authManager->user()->hasRole('admin');

        $categories = $categoriesFetcher->fetch()->results;
        $users = $isAdmin ? $usersFetcher->fetch()->results : [];

        return view('admin.news.create', compact('categories', 'users', 'isAdmin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewsRequest $request, CreateHandler $createNewsUseCase, AuthManager $authManager): RedirectResponse
    {
        $request->validated();

        try {
            $user = $authManager->user();
            $userId = $request->get('user_id', $user->id);

            $command = new Command(
                title: $request->get('title'),
                content: $request->get('content'),
                userId: $userId,
                categoryId: $request->get('category_id'),
                publishedAt: $request->get('published_at'),
                isDraft: $request->get('is_draft', false),
            );

            $news = $createNewsUseCase->handle($command);

            if (!$news->isDraft) {
                NewsPublished::dispatch($news->id, $news->title, $news->content);
            }

        } catch (NewsNotFoundException) {
            throw new NotFoundHttpException('News not found');
        }

        return redirect()->route('admin.news.index');
    }
}
