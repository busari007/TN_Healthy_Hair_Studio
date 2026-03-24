@extends('layouts.base')

@section('content')
<div class="w-full min-h-screen flex items-center justify-center bg-black px-4">
    <form method="POST" action="{{ route('login') }}"
        class="flex flex-col w-full max-w-lg bg-white p-6 rounded-lg shadow-lg space-y-6 mt-20">
        
        @csrf

        <h2 class="text-lg lg:text-2xl font-bold text-center text-gray-800 Playfair">
            Sign In to Your Account
        </h2>

{{-- ERROR MESSAGES --}}
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


        {{-- SUCCESS MESSAGES --}}
        @if(session('success'))
            <p class="text-green-600 text-sm text-center">
                {{ session('success') }}
            </p>
        @endif

        {{-- EMAIL --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 Lato">Email Address:</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Enter your email address"
                class="w-full text-sm mt-1 p-2 border border-gray-300 rounded-lg focus:ring focus:ring-pink-200 Lato"
                required
                autofocus
            >
        </div>

        {{-- PASSWORD --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 Lato">Password:</label>
            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                class="w-full text-sm mt-1 p-2 border border-gray-300 rounded-lg focus:ring focus:ring-pink-200 Lato"
                required
            >
        </div>

        {{-- REMEMBER ME --}}
        <div class="flex items-center">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            <label for="remember" class="text-sm text-gray-600">Remember me</label>
        </div>

        {{-- BUTTON --}}
        <button
            type="submit"
            class="w-full py-2 bg-[#F0CCCE] hover:bg-[#e2babc] text-black font-bold rounded-lg transition-all Lato hover:cursor-pointer"
        >
            Sign In
        </button>

        {{-- FORGOT PASSWORD LINK --}}
        <p class="text-center text-sm mt-3 text-gray-600">
            Forgot your password?
            <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">Reset it here</a>
        </p>

        {{-- SIGN UP LINK --}}
        <p class="text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('signup') }}" class="text-pink-600 hover:underline">Sign up</a>
        </p>

    </form>
</div>
@endsection