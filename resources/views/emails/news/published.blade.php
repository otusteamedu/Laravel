@component('mail::message')
    # Новая новость: {{ $news->title }}

    {{ $news->content }}

    @component('mail::button', ['url' => url("/news/{$news->id}")])
        Читать новость
    @endcomponent

    Спасибо,<br>
    {{ config('app.name') }}
@endcomponent
