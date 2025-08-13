<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class BlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $blogs = Blog::all();

        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('blogs.edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        Request $request,
        \Illuminate\Contracts\Validation\Factory $validationFactory,
        \Illuminate\Contracts\Auth\Factory $auth
    ): RedirectResponse {

        try {
            echo 'dump blogsController 1'.PHP_EOL;
            dump($request);
            $blog = new Blog(['title' => $request->title, 'preview' => $request->preview, 'text' => $request->text]);
            echo 'dump blogsController 2'.PHP_EOL;
            dump($blog);

            $blog->save();
        } catch (InvalidArgumentException $e) {
            // Обработка ошибок валидации
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (Exception $e) {
            // Обработка остальных исключений
            // В этом случае можно перенаправить на страницу с общей ошибкой или логировать ошибку
            return redirect()->back()->withInput()->withErrors(['general_error' => 'Произошла непредвиденная ошибка.']);
        }

        return redirect()
            ->route('blogs.index')
            ->with('success', 'Блог успешно создан');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog, Factory $viewFactory)
    {
        return $viewFactory->make('blogs.show', compact('blog'));
    }

    /**
     * - Здесь не передаем модель, а только примитивные данные, которые необходимы для шаблона
     * - Не используем compact()
     */
    public function edit(Blog $blog): View
    {
        return view('blogs.edit', [
            'blogId' => $blog->id,
            'title' => $blog->title,
            'preview' => $blog->preview,
            'text' => $blog->text,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {

        dump($request);
        exit();

        $requestData = $request->validated();

        $blog->title = $requestData['title'];
        $blog->preview = $requestData['preview'];
        $blog->text = $requestData['text'];
        $blog->save();

        return redirect()->route('blogs.show', ['blog' => $blog]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        //
    }
}
