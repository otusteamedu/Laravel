@extends('layouts.main')

@section('title')
    @lang('registration.register')
@endsection

@section('content')

    <div class="bg-white shadow-lg rounded-lg p-6 md:p-8 w-full max-w-md m-auto mb-4 mt-4">
        <h1 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-4 md:mb-6 text-center">@lang('registration.register')</h1>
        <form class="space-y-4 md:space-y-6">
            <div>
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" placeholder="Your Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div>
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" placeholder="Your Email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div>
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" placeholder="Password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div>
                <label for="confirm-password" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                <input type="password" id="confirm-password" placeholder="Confirm Password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">Register</button>
            <p class="text-center text-gray-600 text-sm">Already have an account? <a href="#" class="text-blue-500 hover:underline">Login</a></p>
        </form>
    </div>

@endsection
