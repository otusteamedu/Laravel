@php
/**
* @var array <int, AreaDTO> $areas
*/
@endphp
<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 text-gray-900">
                    <!-- /.card -->
                    <div class="row" id="area">
                        <div class="col-12">
                            <div name="header" class="mb-3">
                                <div class="flex items-center justify-between">
                                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                        {{ __('account.list_areas') }}
                                    </h2>
                                    @include('components.button.create', ['name'=> 'area', 'route' => route('area.create')])
                                </div>
                            </div>
                            <div name="body">
                                @if($response->success)
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table table-bordered table-hover">
                                                <!-- ./card-header -->
                                                <thead>
                                                    <tr style="vertical-align: middle; text-align: center;">
                                                        <th class="w-10">№</th>
                                                        <th class="w-50">{{ __('account.name_area') }}</th>
                                                        <th class="w-30">{{ __('account.created_at') }}</th>
                                                        <th class="w-10">{{ __('account.action') }}</th>
                                                    </tr>
                                                </thead>
                                                <!-- /.card-body -->
                                                <tbody>
                                                    @foreach($response->data as $area)
                                                        <tr style="vertical-align: middle;" data-widget="expandable-table-{{$area->id}}" aria-expanded="false" id="{{$area->id}}">
                                                            <td>{{$area->id}}</td>
                                                            <td>{{$area->name}}</td>
                                                            <td>{{$area->created_at}}</td>
                                                            <td style="align-items: center;">
                                                                <div style="display: flex;" class="gap-10">
                                                                    @include('components.button.edit', [
                                                                        'route' => route('area.edit', ['area' => $area->id]), 
                                                                        'name' => 'area'])
                                                                        @include('components.button.delete', [
                                                                        'route' => route('area.destroy', ['area' => $area->id]), 
                                                                        'name' => 'area'])
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center justify-between">
                                        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                                            {{ __('account.absence_list_areas') }}
                                        </h1>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@vite('resources/views/area/index/js/index.js')