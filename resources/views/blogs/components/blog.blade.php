

    <div class="newscont">
         <div class="newscont__id">
            <a class="" href="{{ route('blogs.show', ['blog' => $blogId]) }}">{{ $blogId }}</a>
        </div>
        <div class="newscont__title">
            <a class="" href="{{ route('blogs.show', ['blog' => $blogId]) }}">{{ $title }}</a>
        </div>
        <div class="newscont__date">
            {{ $date }}
        </div>
        <div class="newscont__action">
            <a class="" href="{{ route('blogs.show', ['blog' => $blogId]) }}">Read more</a>
            <a class="" href="{{ route('blogs.edit', ['blog' => $blog]) }}">Edit</a>
        </div>
        <div class="newscont__author">
            {{ $author_id }}
        </div>
    </div>

