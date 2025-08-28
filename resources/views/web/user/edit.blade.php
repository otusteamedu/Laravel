@extends('layouts.app')

@section('title', 'Редактирование профиля')

@section('content')
    <form method="POST" action="/register/">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{$name}}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Date -->
        <div class="mt-4">
            <x-input-label for="date" :value="__('Date')" />
            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" value="{{$date}}" required />
            <x-input-error :messages="$errors->get('date')" class="mt-2" />
        </div>
        <!-- Text -->
        <div class="mt-4">
            <x-input-label for="text" :value="__('Text')" />
            <x-text-input id="text" class="block mt-1 w-full" type="text" name="text" value="{{$text}}" required />
            <x-input-error :messages="$errors->get('text')" class="mt-2" />
        </div>
    </form>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/edit.css') }}">
@endsection

@section('script')
    <link rel="stylesheet" href="{{ asset('js/user/edit.js') }}">  
@endsection