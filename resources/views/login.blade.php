@extends('layouts.main')
@section('title', __('main.login'))

@section('content')
    <h1>{{ __('main.login') }}</h1>
    <form>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">{{ __('login.email') }}</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">{{ __('login.password') }}</label>
            <input type="password" class="form-control" id="exampleInputPassword1">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">{{ __('login.rememberme') }}</label>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('login.submit') }}</button>
    </form>
@endsection
