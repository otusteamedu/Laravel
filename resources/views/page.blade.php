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
@endsection
