<?php

namespace App\Http\Controllers\Admin\News;

use App\Application\UseCases\News\Queries\FetchNewsById\Fetcher;
use App\Application\UseCases\News\Queries\FetchNewsById\Query;
use App\Domain\News\Exceptions\NewsNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowController extends Controller
{
    public function __invoke(Fetcher $fetcher, string $newsId): View
    {
        try {
            $query = new Query((int)$newsId);
            $news = $fetcher->fetch($query);

        } catch (NewsNotFoundException) {
            throw new NotFoundHttpException('Новость не найдена');
        }

        return view('admin.news.show', compact('news'));
    }
}
