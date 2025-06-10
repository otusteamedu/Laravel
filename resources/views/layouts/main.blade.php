@extends('layouts.root')

@section('head')
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <link rel="apple-touch-icon" sizes="180x180" href="{{ Vite::asset('resources/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ Vite::asset('resources/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ Vite::asset('resources/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ Vite::asset('resources/favicon/site.webmanifest') }}">
@endsection

@section('body')
<header class="sticky-top">
    @include('navbar.navbar')
</header>

<main class="my-2 container">
    @yield('content')
</main>

<footer>
    @include('partials.footer')
</footer>

@if(session('error'))
    <x-toast :success=false message="{{ session('error') }}"></x-toast>
@endif
@if(session('success'))
    <x-toast :success=true message="{{ session('success') }}"></x-toast>
@endif
<form name="join-form" id="join-form" method="post" action="">
    @csrf
    @method('patch')
</form>
@endsection
@push('scripts-bottom')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('projects', {
            leftForm: {
                action: null,
                projectName: null,
            },

            join(path)
            {
                form = document.getElementById('join-form')
                form.action = path

                form.submit()
            },

            left(path, projectName)
            {
                this.leftForm.action = path
                this.leftForm.projectName = projectName
            },

            clearForm()
            {
                this.leftForm = {
                    action: null,
                    projectName: null,
                }
            },
        });
    });
</script>
@endpush
