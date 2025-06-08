@php
/**
* @var string $route
* @var string $name
*/
@endphp

<button type="button" class="btn btn-primary create-{{ $name }}-btn" data-route="{{ $route }}">
    + {{ __('account.create_btn') }}
</button>