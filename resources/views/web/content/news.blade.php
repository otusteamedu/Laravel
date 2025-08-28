@extends('layouts.app')

@section('title', 'Новости')

@section('content')
    <div class="container">
        <h1 class="h3 text-center page-header">Новости</h1>
        <div class="container">
            <div class="row">
                <div class="col-md-8">  
                    @foreach($news as $item)  
                    <div class="wp-block property list">
                        <div class="wp-block-body">
                            <div class="wp-block-img">
                                <a href="#">
                                    <img src="{{ $item['photo'] }}" alt="">
                                </a>
                            </div>
                            <div class="wp-block-content">
                                <small>
                                    <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>{{ $item['date'] }}</small>
                                    <h4 class="content-title">{{ $item['name'] }}</h4>
                                    <p class="description">{{ $item['text'] }}</p>
                                    <span class="pull-left">
                                        <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>  
                                        <a class="text-blue-500 hover:underline" href="#">Редактировать</a>
                                        <a class="text-blue-500 hover:underline" href="#">Удалить</a>
                                    </span>
                                    <span class="pull-right">
                                        <span class="capacity">
                                            <i class="fa fa-user"></i> {{ $item['user'] }}
                                        </span>
                                    </span>
                                </div>
                        </div>
                        <div class="wp-block-footer">
                            <ul class="aux-info">
                                <li>
                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> 
                                    <a href="#">Просмотреть</a>
                                </li>
                                <li><span class=" glyphicon glyphicon-comment" aria-hidden="true"></span> 5</li>
                                <li><span class="glyphicon glyphicon-star" aria-hidden="true"></span> 2</li>
                                <li><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> +5 <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span></li>
                                <li><span class="glyphicon glyphicon-tags" aria-hidden="true"></span> <a href="#">{{ $item['name'] }}</a></li>
                            </ul>
                        </div>
                    </div>
                    @endforeach
                    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
                        <a class="pagination-previous" href="#">Previous</a>
                        <a class="pagination-next" href="#">Next</a>                     
                        <ul class="pagination-list">                            
                            @for($page=0;$page<$pagination['count'];$page++)                                
                                @if ($page == $pagination['page'])
                                    <li class="is-current"><a class="pagination-link " aria-label="Page {{ $page }}" aria-current="page">{{ $page+1 }}</a></li>
                                @else
                                    <li><a class="pagination-link" href="#" aria-label="Page {{ $page }}">{{ $page+1 }}</a></li>
                                @endif
                            @endfor
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/content/news.css') }}">
@endsection

@section('script')
    <link rel="stylesheet" href="{{ asset('js/content/news.js') }}">  
@endsection