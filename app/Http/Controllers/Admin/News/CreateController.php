<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewsRequest;
use App\Services\Category\Repositories\CategoryRepositoryInterface;
use App\Services\Category\Results\Fetcher as CategoryFetcher;
use App\Services\News\Exceptions\UserNotFoundException;
use App\Services\News\Handlers\ShowHandler;
use App\Services\News\Results\NewsDTO;
use App\Services\User\Results\Fetcher as UserFetcher;
use App\Services\News\Handlers\CreateHandler;
use App\Services\News\Commands\CommandDTO;
use App\Services\User\Repositories\UserRepositoryInterface;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Events\NewsPublished;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateController extends Controller
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository, private UserRepositoryInterface $userRepository, private CategoryFetcher $categoryFetcher, private UserFetcher $userFetcher)
    {
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(AuthManager $authManager): View
    {
        $categories = $this->categoryFetcher->fetch($this->categoryRepository->fetchAll())->results;

        $isAdmin = $authManager->user()->hasRole('admin');

        $users = $isAdmin ? $this->userFetcher->fetch($this->userRepository->fetchAll())->results : [];

        return view('admin.news.create', compact('categories', 'users', 'isAdmin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewsRequest $request, CreateHandler $createNewsUseCase, ShowHandler $showNewsUseCase, AuthManager $authManager): RedirectResponse
    {
        $request->validated();

        $user = $authManager->user();
        $isAdmin = $user->hasRole('admin');
        $userId = $request->get('user_id', $user->id);

        try {
            $newsId = $createNewsUseCase(
                new CommandDTO(
                    $request->get('title'),
                    $request->get('content'),
                    $userId,
                    $request->get('category_id'),
                    $request->get('published_at'),
                    $request->get('is_draft',
                                  false
                    )
                ),
                $isAdmin
            );

            if ($newsId) {

                /** @var NewsDTO $newsDto */
                $newsDto = $showNewsUseCase($newsId);

                if (!$newsDto->isDraft) {
                    NewsPublished::dispatch($newsDto->id, $newsDto->title, $newsDto->content);
                }
            }
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException('News not found');
        }

        return redirect()->route('admin.news.index');
    }
}
