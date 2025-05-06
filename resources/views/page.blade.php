@extends('layouts.main')

@section('title', 'Home page')

@section('head')

    @parent
    <style>
        body {
            background: #efc9c9;
        }
    </style>
@endsection

@section('content')
    <h1>Hello from bootstrap</h1>
    <x-super-input label="Name" type="text"></x-super-input>
    <x-super-input label="Password" type="password"></x-super-input>

@endsection
