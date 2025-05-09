@props(['errors'])

@if ($errors)
    <ul class="invalid-feedback mb-0">
        @foreach ((array) $errors as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
