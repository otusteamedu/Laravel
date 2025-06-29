<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Services\News\Handlers\DestroyHandler;
use App\Services\News\Exceptions\UserNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DestroyController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function __invoke(DestroyHandler $destroyNewsUseCase, string $newsId): RedirectResponse
    {
        Gate::authorize('news.delete', $newsId);

        try {
            $destroyNewsUseCase((int)$newsId);
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException('News not found');
        }

        return redirect()->route('admin.news.index')->with('success', 'News has been deleted');
    }
}
