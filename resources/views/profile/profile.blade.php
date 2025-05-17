@extends('layouts.main')
@section('title', 'ToDo: Профиль пользователя.')

@section('content')
<div class="col-12">
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="row g-0">
                <nav class="col-lg-3 border-end">
                    @include('profile.partials.nav')
                </nav>
                <div class="col-lg-9 tab-content">
                    @include('profile.partials.account-tab')
                    @include('profile.partials.security-tab')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
