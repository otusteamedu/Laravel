<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('account.fibonachi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __('account.fibonachi_number') }}
                </div>
                <div class="p-6 text-gray-900">
                    <div class="input-group">
                        <span class="input-group-text" id="visible-addon">{{ __('account.fibonachi_input') }} </span>
                        <input type="text" class="form-control" placeholder="от 1 до 100" aria-label="Number" aria-describedby="visible-addon" id="fib-input">
                        <button type="button" class="btn btn-outline-primary">{{ __('account.fibonachi_button') }}</button>
                    </div>
                    <div id="fib-result" 
                        class="mt-4 alert alert-info" 
                        style="display:none;" 
                        data-success="{{ __('account.fibonachi_output') }}"
                        data-error="{{ __('account.fibonachi_danger') }}"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
