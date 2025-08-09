

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
                <div class="mt-8">
                    <ul class="flex">
                        <li class="mx-1 px-3 py-2 bg-gray-200 text-gray-500 rounded-lg">
                            <a class="flex items-center font-bold" href="#">Предыдущая</a>
                        </li>
                        <li class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-700 hover:text-gray-200 rounded-lg">
                            <a class="font-bold" href="#">1</a>
                        </li>
                        <li class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-700 hover:text-gray-200 rounded-lg">
                            <a class="font-bold" href="#">2</a>
                        </li>
                        <li class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-700 hover:text-gray-200 rounded-lg">
                            <a class="font-bold" href="#">3</a>
                        </li>
                        <li class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-700 hover:text-gray-200 rounded-lg">
                            <a class="flex items-center font-bold" href="#">Следующая</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/index.css') }}">    
@endsection
    