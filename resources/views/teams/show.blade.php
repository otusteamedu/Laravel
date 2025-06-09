@php
    use App\Services\Team\TeamData;
    use Illuminate\Support\Facades\Storage;
    /**
    * @var TeamData $team
    */
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Просмотр команды
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($team->logo_path)
                    <img src="{{ Storage::disk('public')->url($team->logo_path) }}" alt="Эмблема команды">
                @endif
                <p>
                    {{ $team->nickname }} ({{ $team->name }})
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
