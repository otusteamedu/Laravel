@php
    use App\Models\Blog;
    use App\Models\User;

@endphp

@extends ('layouts.main')

@php
/**
 * @var Blog[] $blogs
 */
@endphp

@section('content')
    <div class="flex items-center justify-between">
        <h1>Blogs</h1>
        <button class="mb-40">
            <a href="{{ route('blogs.create') }}">Create Blog</a>
        </button>
    </div>
    <div class="newscont">
         <div class="newscont__id">
            Id
        </div>
        <div class="newscont__title">
            Title
        </div>
        <div class="newscont__date">
            Date_create
        </div>
        <div class="newscont__action">
            Action
        </div>
        <div class="newscont__author">
            Author_id
        </div>
    </div>



    @foreach($blogs as $blog)
            @include('blogs.components.blog', [
                'blogId' => $blog->id,
                'date' => $blog->created_at->format('d-m-Y H-i-s'),
                'title' => $blog->title,
                'preview' => $blog->preview,
                'author_id' => User::where('id', $blog->author_id)->first()->name,
                'body' => $blog->text,
                'blog' => $blog,
            ])
    @endforeach

@endsection
