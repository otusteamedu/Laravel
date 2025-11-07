@extends('layout.root')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
@endsection

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-3">@include('layout.sidebar')</div>
            <div class="col-9">@yield('content')</div>
        </div>
    </div>
@endsection
