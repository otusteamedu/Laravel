@extends('layouts.app')

@section('title', 'Главная')

@php
    /**
     * @var \App\Services\DTO\News\NewsDTO $news
     */
@endphp

@php
    /**
     * @var \App\Services\DTO\Categories\CategoryDTO $category
     */
@endphp

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-7">
            <h2 class="mb-4">Последние новости</h2>
            @if(count($latestNews) > 0)
                @foreach($latestNews as $news)

                    <div class="card mb-4 news-card shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <div class="placeholder-300x200 img-fluid rounded-start"></div>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $news->title }}</h5>
                                    <p class="card-text text-muted">
                                        <small>
                                            <span><i class="fas fa-folder me-1"></i> <a href="#" class="text-decoration-none">{{ $news?->category?->name }}</a></span>
                                            <span><i class="fas fa-calendar me-1"></i> {{ $news->publishedAt->format('d.m.Y') }}</span>
                                            <span><i class="fas fa-user me-1"></i> {{ $news?->author?->name }}</span>
                                        </small>
                                    </p>
                                    <p class="card-text">{{ $news->content }}</p>
                                    <a href="#" class="btn btn-sm btn-outline-primary">Читать полностью</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

               {{-- <div class="d-flex justify-content-center mt-4">
                    <nav>
                        {{ $latestNews->links() }}
                    </nav>
                </div>--}}
            @else
                <div class="alert alert-info">
                    Пока нет новостей. Заходите позже!
                </div>
            @endif
        </div>

        <div class="col-lg-4 col-md-5 mt-4 mt-md-0">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Популярные категории</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($popularCategories as $category)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="#" class="text-decoration-none">{{ $category->name }}</a>
                                <span class="badge bg-primary rounded-pill">{{ $category->newsCount ?:0 }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
