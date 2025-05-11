@extends('layouts.root')

@section('head')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/main.js'])
@endsection

@section('body')
    @include('layouts.header')
    @yield('content')
@endsection

@section('body-bottom')
    @include('layouts.footer')
@endsection
