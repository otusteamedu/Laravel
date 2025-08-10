<?php

namespace App\Http\Controllers\News;

use App\Http\Requests\UpdateNewsRequest;
use App\Services\UseCases\Commands\News\Update\Command;
use App\Services\UseCases\Commands\News\Update\Handler;
use App\Services\UseCases\Commands\News\Update\NewsNotFoundException;
use Illuminate\Contracts\View\View;
use App\Models\News;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Update
{
    public function __construct(
    ) {
    }

    /**
     * - Здесь не передаем модель, а только примитивные данные, которые необходимы для шаблона
     * - Не используем compact()
     */
    public function edit(AuthManager $auth,int $newsId): View
    {
        $news = News::query()->find($newsId);

        if (!$news) {
            throw new NotFoundHttpException();
        }

        return view('web.admin.edit', [
            'newsId'    => $news->id,
            'name'     => $news->name,
            'text'      => $news->text,
            'photo'  => $news->photo,
            'userId'  => $news->user_id,
        ]);  
    }

    public function update(AuthManager $auth,UpdateNewsRequest $request,int $newsId): RedirectResponse
    {
        if (!$auth->check()) {
            return redirect()->route('login');
        }
        $requestData = $request->validated();
        $news = News::query()->find($newsId);
        $news->name = $requestData['name'];
        $news->text = $requestData['text'];
        $news->save();
        return redirect()->route('news.edit', ['newsId' => $newsId]);
    }
}