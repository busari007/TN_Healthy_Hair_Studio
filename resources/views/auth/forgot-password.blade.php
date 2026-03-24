@extends('layouts.base')

@section('content')
<div class="w-full min-h-screen flex items-center justify-center bg-black px-4">
    <form method="POST" action="{{ route('password.email') }}" 
        class="flex flex-col w-full max-w-lg bg-white p-6 rounded-lg shadow-lg space-y-6 mt-20">
        @csrf

        <h2 class="text-lg lg:text-2xl font-bold text-center text-gray-800 Playfair">
            Reset Your Password
        </h2>

        <p class="text-sm text-gray-600 text-center Lato">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
        </p>

        {{-- STATUS MESSAGE (Success) --}}
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        {{-- ERROR MESSAGES --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 Lato">Email Address:</label>
            <input type="email" name="email" :value="old('email')" required autofocus
                class="w-full text-sm mt-1 p-2 border border-gray-300 rounded-lg focus:ring focus:ring-pink-200 Lato">
        </div>

        <button type="submit" 
            class="w-full py-2 bg-[#F0CCCE] hover:bg-[#e2babc] text-black font-bold rounded-lg transition-all Lato hover:cursor-pointer">
            Email Password Reset Link
        </button>

        <p class="text-center text-sm mt-3 text-gray-600">
            <a href="{{ route('signin') }}" class="text-pink-600 hover:underline">Back to Login</a>
        </p>
    </form>
</div>
@endsection
