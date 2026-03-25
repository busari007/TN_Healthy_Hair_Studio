<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- ✅ DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    
    @livewireStyles

    <style>
        /* Toast Animation */
        .toast { animation: slideIn 0.3s ease-out; }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        /* Custom Bounce for the bell */
        .animate-bounce-slow {
            animation: bounce 2s infinite;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <x-banner />
    <div class="min-h-screen bg-gray-100">
        @include('navigation-menu')
        
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif
        
        <main>
            @yield('content')
        </main>
    </div>

    <!-- ✅ FLOATING NOTIFICATION COMPONENT -->
    @auth
        <div x-data="{ open: false }" class="fixed bottom-6 right-6 z-50" @click.outside="open = false">
            
            <!-- PULSE RING (Only shows when there are unread notifications) -->
            @if(auth()->user()->unreadNotifications->count() > 0)
                <div x-show="!open" class="absolute inset-0 rounded-full bg-red-500 opacity-75 animate-ping"></div>
            @endif

            <!-- BUTTON -->
            <button @click="open = !open" class="relative flex items-center justify-center w-16 h-16 rounded-full bg-red-600 hover:bg-red-700 text-white shadow-2xl transition transform hover:scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-8 w-8 {{ auth()->user()->unreadNotifications->count() > 0 ? 'animate-bounce' : '' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 01-6 0h6z"/>
                </svg>
                
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute -top-1 -right-1 bg-white text-red-600 text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center shadow">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </button>

            <!-- DROPDOWN -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="absolute bottom-20 right-0 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 border border-gray-100 dark:border-gray-700">
                
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold dark:text-white text-sm">Notifications</h3>
                    @if(auth()->user()->unreadNotifications->isNotEmpty())
                        <form method="POST" action="{{ route('notifications.readAll') }}">
                            @csrf
                            <button type="submit" class="text-[10px] uppercase font-bold text-indigo-600 hover:underline">Mark all as read</button>
                        </form>
                    @endif
                </div>

                <div class="max-h-64 overflow-y-auto">
                    @forelse(auth()->user()->notifications()->latest()->take(10)->get() as $notification)
                        <div class="border-b last:border-0 dark:border-gray-700 py-2 px-2 {{ is_null($notification->read_at) ? 'bg-blue-50 dark:bg-gray-700/50' : '' }} rounded mb-1">
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                @csrf
                                <button type="submit" class="w-full text-left">
                                    <p class="font-medium text-xs dark:text-gray-200">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-[11px] leading-tight">{{ $notification->data['message'] ?? '' }}</p>
                                    <span class="text-[9px] text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-400 text-xs py-4 text-center">No notifications yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endauth

    <div id="toast-container" class="fixed top-5 right-5 z-[60] space-y-2"></div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    @stack('scripts')
    @stack('modals')
    @livewireScripts
</body>
</html>
