

@extends('layouts.app')

@section('title', 'Новости в админ панели')

@section('content')
    <div class="container">
        <h1 class="h3 text-center page-header">Новости</h1>
        <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">
            <a href="{{ route('news.create') }}">Создание новости</a>
        </button>
    </div>
    @if (session()->has('success'))
        <div class="bg-green-200 p-6 rounded-xl border-2 border-green-300">
            {{ session('success') }}
        </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-8">    
                @foreach($news as $item)
                        @include('web.admin.components.item', [
                            'newsId' => $item->id,
                            'date' => $item->create_at,
                            'name' => $item->name,
                            'body' => $item->text,
                            'photo'=>$item->photo,
                            'author' => $item->user_id,
                            'news' => $item,
                        ])
                @endforeach
                <nav class="pagination is-centered" role="navigation" aria-label="pagination">
                    <a class="pagination-previous" href="{{ route('news.indexpage',['num' => 0]) }}">Previous</a>
                    <a class="pagination-next" href="{{ route('news.indexpage',['num' => $pagination['page']+1]) }}">Next</a>                     
                    <ul class="pagination-list">                            
                        @for($page=0;$page<$pagination['count'];$page++)                                
                            @if ($page == $pagination['page'])
                                <li class="is-current"><a class="pagination-link " aria-label="Page {{ $page }}" aria-current="page">{{ $page+1 }}</a></li>
                            @else
                                <li><a class="pagination-link" href="{{ route('news.indexpage',['num' => $page]) }}" aria-label="Page {{ $page }}">{{ $page+1 }}</a></li>
                            @endif
                        @endfor
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/index.css') }}">    
@endsection
    