@extends('layout.main')

@section('title', 'Test Page')

@section('head')
    @parent
    <style>
        body {
            background: lightcoral;
        }
    </style>
@endsection

@section('content')
    <h1>Test page</h1>
@endsection

@section('body-bottom')
    123
@endsection
