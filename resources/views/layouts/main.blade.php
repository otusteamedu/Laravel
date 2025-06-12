@extends ('layouts.root')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link href="{{ asset('css/style.css') }}"  rel="stylesheet">
@endsection

@section('head-bottom')
    <div class="container">
        <div class="row">
            <div class="col-3 menu-item">
                <a href="/ru/blogs">RU </a> &nbsp;&nbsp;&nbsp;&nbsp;<a href="/en/blogs">EN </a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-3 menu-item">
                <a href="/{{ app()->getLocale() }}/blogs">
                {{  app()->getLocale() === "en" ? "Blog list: " : 'К списку блогов:' }} </a>
            </div>
        </div>
    </div>
@endsection

@section ('body')
    <div class="row text-center">
        @yield('content')

    </div>
@endsection


