<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\News;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Str;
use Carbon\Carbon;
class NewsController extends Controller
{
    private $obj;
    public function __construct(){
        $this->obj = new News;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $news = News::all();
        return view('web.admin.index', ['news'=>$news]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('web.admin.edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request, \Illuminate\Contracts\Validation\Factory $validationFactory,
    \Illuminate\Contracts\Auth\Factory $auth)
    {
        $validator = $validationFactory->make(request()->all(), [
            'name' => ['required', 'min:10', 'max: 255'],
            'text' => ['required', 'min:10'],
        ]);

        try {
            $validator->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
            ;
        }

        $news = new News();
        $news->name = $request->input('name');
        $news->text = $request->input('text');
        $news->link = Str::slug($request->input('name'));
        $news->preview = Str::limit($request->input('text'), 20, '...');
        $news->user_id = $auth->guard()->id();
        $news->create_at = Carbon::now()->format('Y-m-d');
        $news->save();
        return redirect()->route('news.index')->with('success', 'Новость успешно создана');
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        return view('web.admin.show', compact('news')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        return view('web.admin.edit', [
            'newsId'    => $news->id,
            'name'     => $news->name,
            'text'      => $news->text,
            'photo'  => $news->photo,
            'userId'  => $news->user_id,
        ]);   
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, News $news)
    {
        $requestData = $request->validated();
        $news->name = $requestData['name'];
        $news->text = $requestData['text'];
        $news->save();
        return view('web.admin.edit', [
            'newsId'    => $news->id,
            'name'     => $news->name,
            'text'      => $news->text,
            'photo'  => $news->photo,
            'userId'  => $news->user_id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        //
    }
}
