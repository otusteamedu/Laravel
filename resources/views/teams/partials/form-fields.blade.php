@php
    use App\Services\Team\TeamData;
    /**
    * @var TeamData $team
    */
@endphp
<div>
    <x-input-label for="name">Название</x-input-label>
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $team?->getName() ?? null)"
        required autofocus autocomplete="off" />
    <x-input-error class="mt-2" :messages="$errors->get('name')" />
</div>

<div>
    <x-input-label @class(['mt-2']) for="nickname">Псевдоним</x-input-label>
    <x-text-input id="nickname" name="nickname" type="text" class="mt-1 block w-full"
        :value="old('nickname', $team?->getNickname() ?? null)"  required autofocus autocomplete="off" />
    <x-input-error class="mt-2" :messages="$errors->get('nickname')" />
</div>

<div>
    <x-input-label @class(['mt-2']) for="file">"Эмблема"</x-input-label>
    <x-text-input id="file" name="file" type="file" class="mt-1 block w-full" autofocus/>
    <x-input-error class="mt-2" :messages="$errors->get('file')" />
</div>

<div>
    <x-input-error class="mt-2" :messages="$errors->get('error')" />
</div>
