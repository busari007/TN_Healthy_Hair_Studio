<div>
    <style>
    .nav-link {
    position: relative;
    color: white;
}

.nav-link::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -4px;
    width: 0;
    height: 2px;
    background: #f472b6;
    transition: width 0.3s;
}

.nav-link:hover::after {
    width: 100%;
}
</style>

<nav 
    x-data="{ open: false }" 
    class="w-full fixed top-0 z-50 bg-black/40 backdrop-blur-md border-b border-white/20"
>
    <div class="max-w-7xl mx-auto flex items-center justify-between px-3 py-3 lg:py-4">

        {{-- LOGO --}}
        <a href="{{ route('home') }}" class="w-11 h-11 lg:w-14 lg:h-14 rounded-full overflow-hidden">
            <img src="{{ asset('images/TN-Skincare logo.webp') }}" class="w-full h-full object-cover">
        </a>

        {{-- DESKTOP NAV --}}
        <div class="hidden lg:flex gap-x-6 text-lg font-semibold">

            <a href="{{ route('home') }}" class="nav-link">Home</a>

            <a href="{{ route('home') }}#services" class="nav-link">Services</a>
        
            @auth
            @if(auth()->user()->role !== 'admin')
                <a href={{ route('bookings') }} class="nav-link">Bookings</a>
            @endif
            @endauth

            <a href="{{ route('home') }}#contact" class="nav-link">Contact Us</a>


            {{-- ADMIN --}}
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users') }}" class="nav-link">User's Info</a>
                    <a href="{{ route('admin.booking') }}" class="nav-link">User's Bookings</a>
                @endif
            @endauth

        </div>

        {{-- RIGHT SIDE --}}
        <div class="flex items-center gap-3 Playfair text-white">

            @guest
                <a href="{{ route('signin') }}" class="hidden sm:block">Login</a>
                <a href="{{ route('signup') }}" class="hidden sm:block">Sign Up</a>
            @endguest

@auth
    {{-- PROFILE LINK --}}
    <a href="{{ route('profile.show') }}" class="nav-link hidden sm:block">
        {{ auth()->user()->name }}
    </a>

    {{-- LOGOUT FORM --}}
    <form method="POST" action="{{ route('logout') }}" x-data>
        @csrf
        <button 
            type="submit"
            @click.prevent="$root.submit();"
            class="Playfair hidden sm:block hover:text-gray-200 transition-colors duration-200 text-base nav-link"
        >
            Logout
        </button>
    </form>
@endauth


            {{-- MOBILE MENU BUTTON --}}
            <img
                :src="open ? '{{ asset('images/close.png') }}' : '{{ asset('images/hamburger.png') }}'"
                class="w-6 h-6 lg:hidden cursor-pointer"
                @click="open = !open"
            >
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div x-show="open" class="lg:hidden bg-black/90 text-white px-6 py-6 space-y-4">

        <a href="{{ route('home') }}" class="block">Home</a>
        <a href="{{ route('home') }}#services" class="block">Services</a>
        <a href="{{ route('home') }}#contact" class="block">Contact</a>


        @auth
        @if(auth()->user()->role !== 'admin')
            <a href={{ route('bookings') }} class="block">Bookings</a>

                @elseif(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users') }}" class="block">User's Info</a>
                    <a href="{{ route('admin.booking') }}" class="block">User's Bookings</a>
                @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="block text-left w-full">Logout</button>
            </form>
        @endauth

        @guest
            <a href="{{ route('signin') }}" class="block">Login</a>
            <a href="{{ route('signup') }}" class="block">Sign Up</a>
        @endguest
    </div>
</nav>

</div>