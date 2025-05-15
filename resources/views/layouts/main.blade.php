@extends('layouts.root')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endsection

@section('body')
    <main class="flex-grow-1">
        <div class="container mt-10 mb-10 br-10">
            <div class="row">
                <div class="col-12">@include("partials.header")</div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">@yield('content')</div>
            </div>
        </div>
    </main>
@endsection
@section('body-bottom')
    @include("partials.footer")
@endsection('body-bottom')
