@php
    /**
     * @var int|null $blogId - The ID of the blog (for edit form)
     * @var string|null $title - The title of the blog
     * @var string|null $preview - The preview of the blog
     * @var string|null $text - The text of the blog
     */
@endphp

@extends ('layouts.main')

@section('title', isset($blogId) ? 'Edit Blog' : 'Create Blog')

@section('content')
    <div class="container mx-auto px-4">
        <h1>
            {{ isset($blogId) ? 'Edit Blog' : 'Create Blog'}}
        </h1>

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ isset($blogId) ? route('blogs.update', ['blog' => $blogId, 'locale' => app()->getLocale()]) :
            route('blogs.store', ['locale' => app()->getLocale()]) }}" method="POST">
            @csrf
            @if (isset($blogId))
                @method('PUT')
            @endif

            <div class="mb-4 editfield">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $title ?? '') }}"
                       class="{{$errors->has('title') ? 'invalid' : ''}}"
                       style="width: 1040px"
                       placeholder="min 3 symbols"
                       required>
            </div>

            <div class="mb-4 editfield">
                <label for="preview">Preview</label>
                <textarea name="preview" id="preview" cols="100" rows="5" class=" {{$errors->has('title') ? 'invalid' : ''}}" placeholder="min 7 symbols" required>{{ old('preview', $preview ?? '') }}</textarea>
            </div>

            <div class="mb-4 editfield">
                <label for="text">Text</label>
                <textarea name="text" id="text" cols="100" rows="5" class=" {{$errors->has('title') ? 'invalid' : ''}}" placeholder="min 7 symbols" required>{{ old('text', $text ?? '') }}</textarea>
            </div>

            <div>
                <button type="submit" class="">
                    {{ isset($blogId) ? 'Update' : 'Create' }}
                </button>
            </div>
        </form>
    </div>
@endsection
