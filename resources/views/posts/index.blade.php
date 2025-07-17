<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h1>Posts</h1>

                <ol>
                    @foreach ($posts as $post)
                        <li><a href="{{  route('posts.show', ['post' => $post]) }}"
                                class="hover:underline text-blue-700">{{ $post->title }}</a></li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</x-app-layout>