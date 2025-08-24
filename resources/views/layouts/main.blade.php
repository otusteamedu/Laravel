@extends ('layouts.root')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link href="{{ asset('css/style.css') }}"  rel="stylesheet">
@endsection

@section('head-bottom')

    <div class="container">
        <div class="topmenu">
            <div class="topmenu__col1">
                <a href="/shedules">К списку записей </a>
            </div>
            <div class="topmenu__col2">
                <a href="/dashboard">User: {{ Auth::user()->name }} </a>
            </div>

        </div>
    </div>
@endsection


@section ('body')
    <div class="row text-center">
        @yield('content')

    </div>
@endsection


