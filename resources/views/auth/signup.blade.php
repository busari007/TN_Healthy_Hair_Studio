@extends('layouts.base')

@section('content')

<div class="w-full min-h-screen flex items-center justify-center bg-black px-4">

    <form method="POST" action="{{ route('register') }}"
        class="flex flex-col w-full max-w-lg bg-white p-6 rounded-lg shadow-lg space-y-6 mt-20">

        @csrf

        <h2 class="text-lg lg:text-2xl font-bold text-center text-gray-800 Playfair">
            Create Your Account to Get Started
        </h2>

        {{-- SUCCESS --}}
        @if(session('success'))
            <p class="text-green-600 text-sm text-center">
                {{ session('success') }}
            </p>
        @endif

        {{-- FULL NAME --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 Lato">Full Name:</label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Enter your full name"
                class="w-full text-sm mt-1 p-2 border border-gray-300 rounded-lg focus:ring focus:ring-pink-200 Lato"
                required
            >
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

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
            >
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
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
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- CONFIRM PASSWORD --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 Lato">Confirm Password:</label>
            <input
                type="password"
                name="password_confirmation"
                placeholder="Confirm your password"
                class="w-full text-sm mt-1 p-2 border border-gray-300 rounded-lg focus:ring focus:ring-pink-200 Lato"
                required
            >
        </div>

        {{-- PHONE --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 Lato">Phone Number:</label>
            <input
                type="tel"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="+234 ### ### ####"
                class="w-full text-sm mt-1 p-2 border border-gray-300 rounded-lg focus:ring focus:ring-pink-200 Lato"
                required
            >
            @error('phone')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ADDRESS --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 Lato">Address:</label>
            <input
                type="text"
                name="address"
                value="{{ old('address') }}"
                placeholder="Enter your address"
                class="w-full text-sm mt-1 p-2 border border-gray-300 rounded-lg focus:ring focus:ring-pink-200 Lato"
                required
            >
            @error('address')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- BUTTON --}}
        <button
            type="submit"
            class="w-full py-2 bg-[#F0CCCE] hover:bg-[#e2babc] text-black font-bold rounded-lg transition-all Lato hover:cursor-pointer"
        >
            Sign Up
        </button>

    </form>

</div>

@endsection