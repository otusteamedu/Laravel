@extends('layouts.mainViewTemplate')

@push('mainStyles')
    <style>
        .alertError { color: red; }
        .alertErrorUnder {color: red; margin-top: 10px; }
    </style>
@endpush

@section('mainMenu')
@endsection('mainMenu')

@section('content')

    <h3 class="h3">{{__('Edit user id = ')}}{{$userForEditId}}</h3>
    <form method="POST" action="{{route('updateUserOfMainUp', ['userForEditId'=> $userForEditId])}}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">{{__('User name')}}</label>
            <input type="text" id="name" name="name" class="form-control"
                   value="{{old('name')}}" placeholder="{{__('Enter name for user of main App')}}"/>
            @error('name')
            <p class="alertError">{{$message}}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label for="lastName" class="form-label">{{__('User last name')}}</label>
            <input type="text" id="lastName" name="lastName" class="form-control"
                   value="{{old('lastName')}}" placeholder="{{__('Enter lastName for user of main App')}}"/>
            @error('lastName')
            <p class="alertError">{{$message}}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label for="secondName" class="form-label">{{__('User second name')}}</label>
            <input type="text" id="secondName" name="secondName" class="form-control"
                   value="{{old('secondName')}}" placeholder="{{__('Enter secondName for user of main App')}}"/>
            @error('secondName')
            <p class="alertError">{{$message}}</p>
            @enderror
        </div>


        <input type="reset" class="btn btn-primary" value="{{__('Reset fio')}}"/>
        <input type="submit" class="btn btn-primary" value="{{__('Update fio')}}"/>
    </form>
    @if($msg)
        <h2 class="alertErrorUnder">{{$msg}}</h2>
    @endif

@endsection('content')
