@extends('layouts.main')

@vite('resources/views/area/index/js/index.js')

@php

/**
* @var <AreaDTO> $area
*/
$area = $response->data;

@endphp

@section('content')
    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @include('components.button.back', ['route' => route('area.index'), 'text' => __('account.back_btn')])

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 text-gray-900">
                    <!-- /.card -->
                    <div class="row">
                        <div class="col-12" id="area">
                            <div name="body">
                                <div class="flex align-items-center my-2 gap-3">
                                    <label for="name" class="form-label m-0" style="white-space: nowrap;">{{ __('account.label_name_area') }}</label>
                                    <input type="name" 
                                        name="name-area" 
                                        class="form-control" 
                                        placeholder="{{ __('account.input_placeholder_name_area') }}" 
                                        value="{{ old('name-area', $area->name ?? $response->message) }}"
                                        required>
                                </div>
                                <div class="flex items-center justify-end my-3">
                                    @if($area)
                                        @include('components.button.update', [
                                            'route' => route('area.update', ['area' => $area->id]), 
                                            'name' => 'area', 
                                            'fields' => 'name-area', 
                                            'redirect_route' => route('area.index')])
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
