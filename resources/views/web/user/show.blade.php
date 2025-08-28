@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')    
    <div class="container mx-auto px-4">
        <div class="wp-block property list">
            <div class="wp-block-body">
                <div class="wp-block-img">
                    <a href="#">
                        <img src="{{ $photo }}" alt="">
                    </a>
                </div>
                <div class="wp-block-content">
                    <h1 class="text-2xl font-semibold my-4">
                    {{$name}}
                    </h1>
                    <p>
                        Дата рождения: {{ $date }}
                    </p>
                    <h2>Группа: {{ $group }}</h2>
                    <p>{{$text}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/show.css') }}">
@endsection

@section('script')
    <link rel="stylesheet" href="{{ asset('js/user/show.js') }}">  
@endsection