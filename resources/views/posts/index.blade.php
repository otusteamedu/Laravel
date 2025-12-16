<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Все посты
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <a class="btn btn-primary" href="{{ route('posts.create') }}">
                Создать пост
            </a>
        </div>

        @foreach ($posts as $post)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl mb-4">{{ $post->title }}</h3>
                    <div class="mb-4">{{ $post->text }}</div>
                    <div class="mb-4">
                        @php($liked = $post->likes()->remember(10)->where('user_id', auth()->id())->exists())
                        @if($liked)
                            <form action="{{  route('posts.unlike', ['post' => $post]) }}" method="post">
                                @csrf
                                <button class="btn" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="oklch(63.7% 0.237 25.331)" class="size-[1.2em]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            fill="oklch(63.7% 0.237 25.331)"
                                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                    </svg>

                                    {{ $post->likes()->remember(10)->count() }}
                                </button>
                            </form>
                        @else
                            <form action="{{  route('posts.like', ['post' => $post]) }}" method="post">
                                @csrf
                                <button class="btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor" class="size-[1.2em]">
                                        <path stroke-linecap="round" stroke-linejoin="round" fill="none"
                                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                    </svg>

                                    {{ $post->likes()->remember(10)->count() }}
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="flex gap-4">
                        <small>Время создания: {{ $post->created_at }}</small>
                        <small>Автор: {{ $post->author->name }}</small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-app-layout>
