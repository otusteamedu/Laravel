@extends('layouts.root')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
@endsection

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-3">@include('layouts.sidebar')</div>
            <div class="col-9">@yield('content')</div>
        </div>
    </div>
@endsection
