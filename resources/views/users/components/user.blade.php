@php
    /**
     * @var \App\Models\User $user
     */
@endphp
<div class="mt-6">
    <div class="max-w-4xl px-10 py-6 bg-slate-50 rounded-lg shadow-md">
        <div class="flex justify-between items-center">
            <span class="font-light text-gray-600">{{ $user->created_at->format('F j, Y') }}</span>
        </div>
        <div class="mt-2">
            <a class="text-2xl text-gray-700 font-bold hover:underline" href="{{ route('users.show', ['user' => $user->id]) }}">{{ $user->name }}</a>
        </div>
        <div class="flex justify-end  items-center gap-6 mt-4">
            <a class="text-blue-500 hover:underline" href="{{ route('users.show', ['user' => $id]) }}">View</a>
            <a class="text-blue-500 hover:underline" href="{{ route('users.edit', ['user' => $id]) }}">Edit</a>
        </div>
    </div>
</div>
