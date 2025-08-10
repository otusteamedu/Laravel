@extends('layouts.app')

@section('title', $news->name)
@section('content')
    <div class="pt-16 lg:pt-20">
        <div class="border-b border-grey-lighter pb-8 sm:pb-12">
            <h2 class="block font-body text-3xl font-semibold leading-tight text-primary  sm:text-4xl md:text-5xl">
                {{ $news->name }}
            </h2>
            <div class="flex items-center pt-5 sm:pt-8">
                <p class="pr-2 font-body font-light text-primary">
                    {{ $news->create_at }}
                </p>
                <br>
                <br>
                <span class=" font-body text-grey">//</span>
                <p class="pl-2 font-body font-light text-primary">
                    4 min read
                </p>
                <span class=" font-body text-grey">//</span>
                <br>
                <br>
                <p class="pl-2 font-body font-light text-primary">
                    <a class="text-blue-500" href="{{ route('news.edit', ['newsId' => $news->id]) }}">Edit</a>
                </p>
            </div>
        </div>
        <div class="prose prose max-w-none border-b border-grey-lighter py-8  sm:py-12">
            {!! $news->text !!}
        </div>

        <div class="flex items-center py-10">
            <span class="pr-5 font-body font-medium text-primary ">Share</span>
            <a href="/">
                <i class="bx bxl-facebook text-2xl text-primary transition-colors hover:text-secondary  "></i></a>
            <a href="/">
                <i class="bx bxl-twitter pl-2 text-2xl text-primary transition-colors hover:text-secondary  "></i>
            </a>
            <a href="/">
                <i class="bx bxl-linkedin pl-2 text-2xl text-primary transition-colors hover:text-secondary "></i></a>
            <a href="/">
                <i class="bx bxl-reddit pl-2 text-2xl text-primary transition-colors hover:text-secondary "></i></a>
        </div>
    </div>
@endsection