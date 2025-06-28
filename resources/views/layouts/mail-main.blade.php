@extends('layouts.root')

@section('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet" type="text/css">
@endsection
@section('head-bottom')
<style type="text/css">
    body {
        margin: 0;
        padding: 0;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        font-family: Manrope, sans-serif !important;
    }

    .container {
        padding-left: 32px;
        padding-right: 32px;
    }
</style>
@endsection
@section('body')
<main class="container">
    @yield('content')
</main>
@endsection