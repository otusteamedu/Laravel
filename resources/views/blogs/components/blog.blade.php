
    <div class="newscont">
         <div class="newscont__id">
            {{ $blogId }}
        </div>
        <div class="newscont__title">
            {{ $title }}
        </div>
        <div class="newscont__date">
            {{ $date }}
        </div>
        {{-- <div class="newscont__action">
            <a class="" href="{{ route('blogs.show', ['blog' => $blogId, 'locale' => app()->getLocale()]) }}">
                {{  app()->getLocale() === "en" ? "Read more: " : 'Подробнее:' }}
            </a>
            <a class="" href="{{ route('blogs.show', ['blog' => $blogId, 'locale' => app()->getLocale()]) }}">
                {{  app()->getLocale() === "en" ? "Edit " : 'Редактировать' }}
            </a>

        </div> --}}
    </div>

