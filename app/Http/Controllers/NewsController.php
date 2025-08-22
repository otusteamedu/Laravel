<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\NewsResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
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
    public function index(): JsonResponse
    {
        $news = News::all();
        return new JsonResponse($news);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(StoreNewsRequest $request)
    {
        $news = new News();
        $nameYes = $news::where('name', '=', $request->input('name'))->first();
        if(isset($nameYes->id)){
            return new JsonResponse(['Новость с таким названием уже существует'], 409);
        }
        $news->name = $request->input('name');
        $news->text = $request->input('text');
        $news->link = Str::slug($request->input('name'));
        $news->preview = Str::limit($request->input('text'), 20, '...');
        $news->user_id = Auth::id();
        $news->create_at = Carbon::now()->format('Y-m-d');
        $news->save();
        return new NewsResource($news);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): NewsResource
    {
        $newsItem = News::findOrFail($id);
        return new NewsResource($newsItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, int $id)
    {       
        $requestData = $request->validated();
        $news = News::query()->find($id);
        if($news==null){
            return new JsonResponse(['Новость с таким id не найдена'], 404);
        }
        else{
            $nameYes = $news::where('name', '=', $requestData['name'])->where('id', '!=', $id)->first();
            if(isset($nameYes->id)){
                return new JsonResponse(['Новость с таким названием уже существует'], 409);
            }
        }
        $news->name = $requestData['name'];
        $news->text = $requestData['text'];
        $news->save();
        return new NewsResource($news);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $news = News::query()->find($id);
        $news->delete();
        return new JsonResponse(['Новость с id:'.$id.' удалена'], 204);
    }

    public function testScope(): JsonResponse
    {
        return new JsonResponse(['scopes' => 'working']);
    }
}
