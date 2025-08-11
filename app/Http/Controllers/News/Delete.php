<?php

namespace App\Http\Controllers\News;

use App\Models\News;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;

class Delete
{
    /**
     * Remove the specified resource from storage.
     */
    public function delete(AuthManager $auth,$newsId)
    {
        if (!$auth->check()) {
            return redirect()->route('login');
        }
        $news = News::query()->find($newsId);
        $news->delete();
        return redirect()->route('news.index')
            ->with('success', 'Пост успешно удален');
    }
}