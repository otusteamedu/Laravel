@extends ('layouts.main')

@props(['$blogId', 'title', 'preview', 'text', 'body', ])

@php
/**
 * В этом шаблоне используется модель.
 * @var \App\Models\Blog $blog
 */
@endphp

@section('content')
    <div class="showblog">
        <h2>
            {{ $blog->title }}
        </h2>
        <div class="showblog__date">
            <div>Created: {{ $blog->created_at->format('d-m-Y H-i-s') }}</div>
            <div>Updated: {{ $blog->updated_at->format('d-m-Y H-i-s') }}</div>
        </div>
        <div class="showblog__preview">
            {!! $blog->preview !!}
        </div>
        <div class="showblog__text">
            {!! $blog->text !!}
        </div>

        <div>
            <a href="{{ route('blogs.edit', ['blog' => $blog]) }}">Edit</a>
        </div>

    </div>
@endsection
