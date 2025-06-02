<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Services\News\Exceptions\NewsNotFoundException;
use App\Services\News\Handlers\ShowHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\View;

class ShowController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param ShowHandler $showNewsUseCase
     * @param ViewFactory $view
     * @param int         $newsId
     *
     * @return View
     */
    public function __invoke(ShowHandler $showNewsUseCase, ViewFactory $view, string $newsId): View
    {
        try {
            $news = $showNewsUseCase((int)$newsId);
        } catch (NewsNotFoundException) {
            throw new NotFoundHttpException('News not found');
        }

        return $view->make('admin.news.show', compact('news'));
    }
}
