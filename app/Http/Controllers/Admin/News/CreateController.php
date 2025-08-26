<?php

namespace App\Http\Controllers\Admin\News;

use App\Application\UseCases\Category\Queries\FetchAllCategories\Fetcher as CategoriesFetcher;
use App\Application\UseCases\News\Commands\CreateNews\Command;
use App\Application\UseCases\News\Commands\CreateNews\Handler as CreateHandler;
use App\Application\UseCases\User\Queries\FetchAllUsers\Fetcher as UsersFetcher;
use App\Domain\News\Exceptions\NewsNotFoundException;
use App\Events\NewsPublished;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewsRequest;
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
    public function create(
        AuthManager $authManager,
        CategoriesFetcher $categoriesFetcher,
        UsersFetcher $usersFetcher
    ): View {
        $isAdmin = $authManager->user()->hasRole('admin');
        $categories = $categoriesFetcher->fetch()->items;
        $users = $isAdmin ? $usersFetcher->fetch()->items : [];

        return view('admin.news.create', compact('categories', 'users', 'isAdmin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        CreateNewsRequest $request,
        CreateHandler $createNewsUseCase,
        AuthManager $authManager
    ): RedirectResponse {
        $data = $request->validated();

        try {
            $user = $authManager->user();
            $userId = $data['user_id'] ?? $user->id;

            $publishedAt = $request->get('published_at')
                ? new \DateTimeImmutable($request->get('published_at'))
                : null;

            $command = new Command(
                title: $data['title'],
                content: $data['content'],
                authorId: $userId,
                categoryId: $data['category_id'],
                publishedAt: $publishedAt,
                isDraft: $data['is_draft'] ?? false,
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
