@extends('layouts.main')

@section('content')
    <div class="flex flex-wrap">
        <aside class="bg-white lg:w-64  shadow-lg p-4 md:p-6 w-full">
            @include('blocks.sidebar')
        </aside>
        <main class="flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
        @yield('inner-content')
        </main>
    </div>
@endsection


