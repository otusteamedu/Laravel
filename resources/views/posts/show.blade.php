<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h1 class="mb-4 text-xl">{{ $post->title }}</h1>

                <p>{{ $post->text }}</p>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg mb-4">Comments</h3>
                @forelse($post->comments as $comment)
                    <p>{{ $comment->author->name }}</p>
                    <p>{{ $comment->text }}</p>
                    <hr class="my-4">
                @empty
                    <p>Not yet</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>