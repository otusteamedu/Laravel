<p
    {{ $attributes->merge(
            [
                'x-data' => "{ show: true }",
                'x-show' => "show",
                'x-transition',
                'x-init' => "setTimeout(() => show = false, 4000)",
                'class' => "pl-2 text-sm text-center font-bold",
            ]
        )
    }}
>{{ $slot }}</p>
