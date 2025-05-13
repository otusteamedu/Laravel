@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="px-4 py-5 my-5 text-center">
    <h1 class="display-5 fw-bold text-body-emphasis">{{ __('home.title') }}</h1>
    <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">{{ __('home.description') }}</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="/register" class="btn btn-primary btn-lg px-4 gap-3">Get Started</a>
            <a href="/static" class="btn btn-outline-secondary btn-lg px-4">Learn More</a>
        </div>
    </div>
</div>

<div class="container px-4 py-5">
    <h2 class="pb-2 border-bottom">{{ __('home.sub_title') }}</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4 py-5">
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Responsive Design</h5>
                    <p class="card-text">Our application is fully responsive and works great on all devices.</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">User Profiles</h5>
                    <p class="card-text">Manage your profile and settings with our intuitive interface.</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Easy Registration</h5>
                    <p class="card-text">Simple and secure registration process for new users.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
