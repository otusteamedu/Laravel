<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{$area['name']}}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 text-gray-900">
                    <!-- /.card -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered table-hover">
                                        <!-- ./card-header -->
                                        <thead>
                                            <tr>
                                                <th>№</th>
                                                <th>{{ __('account.name_area') }}</th>
                                                <th>{{ __('account.created_at') }}</th>
                                            </tr>
                                        </thead>
                                        <!-- /.card-body -->
                                        <tbody>
                                            @foreach($areas as $area)
                                            <tr data-widget="expandable-table-{{$area->getId()}}" aria-expanded="false" id="{{$area->getId()}}">
                                                <td>{{$area->getId()}}</td>
                                                <td>{{$area->getName()}}</td>
                                                <td>{{$area->getCreatedAt()}}</td>
                                            </tr>
                                            <tr class="expandable-description-{{$area->getId()}} d-none">
                                                <td colspan="3" style="align-items: center;">
                                                    <div style="display: flex; justify-content: space-between;">
                                                        <button type="button" class="btn-edit">Редактировать</button>
                                                        <button type="button" class="btn-delete">Удалить</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

    @vite('resources/views/area/index/js/index.js')
