<a
    {{ $attributes->merge(
            [
                'class' => "inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded
                    transition",
            ]
        )
    }}
>
    {{ $slot }}
</a>
