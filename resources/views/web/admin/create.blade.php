@extends('layouts.app')

@section('title', 'Создание новости')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold my-4">Создание новости</h1>
        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <form action="{{ route('news.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="formGroupExampleInput">Название новости</label>
                        <input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Example input"  class="{{$errors->has('title') ? 'invalid' : ''}}" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="formGroupExampleInput">Описание новости</label>
                        <textarea name="text" class="form-control" rows="7" cols="70" id="formGroupExampleInput" class=" {{$errors->has('title') ? 'invalid' : ''}}" required></textarea>
                    </div>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Создать</button>
            </div>
        </form>
    </div>
@endsection
