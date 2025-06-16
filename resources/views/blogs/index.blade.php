@php use App\Models\Blog; @endphp
@extends ('layouts.main')

@php
/**
 * @var Blog[] $blogs
 */
@endphp

@section('content')
    <div class="flex items-center justify-between">
        <h1>
            {{  app()->getLocale() === "en" ? "Blogs " : 'Блоги' }}
        </h1>
        <button class="mb-40">
            <a href="{{ route('blogs.create', app()->getLocale()) }}">
                {{  app()->getLocale() === "en" ? "Create Blog" : 'Создать блог' }}
            </a>
        </button>
    </div>

    @foreach($blogs as $blog)
            @include('blogs.components.blog', [
                'blogId' => $blog->id,
                'date' => $blog->created_at->format('d-m-Y H-i-s'),
                'title' => $blog->title,
                'preview' => $blog->preview,
                'body' => $blog->text,
                'blog' => $blog,
            ])
    @endforeach

@endsection
