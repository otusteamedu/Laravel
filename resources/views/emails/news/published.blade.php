@component('mail::message')
    # Новая новость: {{ $title }}

    {{ $content }}

    @component('mail::button', ['url' => url("/news/{$id}")])
        Читать новость
    @endcomponent

    Спасибо,<br>
    {{ config('app.name') }}
@endcomponent
