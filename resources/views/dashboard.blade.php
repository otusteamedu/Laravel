<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <table class="table">
        <tr>
            <th scope="col">{{__('user id')}}</th>
            <th scope="col">{{__('last name')}}</th>
            <th scope="col">{{__('name')}}</th>
            <th scope="col">{{__('second  name')}}</th>
            <th scope="col">{{__('organization')}}</th>
            <th scope="col">{{__('role')}}</th>
            <th scope="col">{{__('Action')}}</a></th>
        </tr>
        <tbody>
    @foreach($usersToShow as $user)
        <tr>
            <td>{{$user->id}}</td>
            <td>{{$user->lastName}}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->secondName}}</td>
            <td>{{$user->organization}}</td>
            <td>{{$user->userRole}}</td>
            <td><a href="{{route('editUserOfMainUp', ['userForEditId' => $user->id])}}">{{__('Edit')}}</td>
        </tr>
    @endforeach
        </tbody>
    </table>
</x-app-layout>
