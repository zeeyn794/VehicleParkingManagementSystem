<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ParkMaster') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @stack('styles')
        
        <style>
            .glass-effect {
                background: rgba(242, 242, 242, 0.98);
                backdrop-filter: blur(10px);
                border: 2px solid rgba(245, 48, 3, 0.45);
                box-shadow:
                    0 18px 55px rgba(17, 17, 16, 0.16),
                    0 0 0 6px rgba(245, 48, 3, 0.12);
            }
            .dark .glass-effect {
                background: rgba(16, 16, 16, 0.94);
                border-color: rgba(245, 48, 3, 0.38);
                box-shadow:
                    0 18px 55px rgba(0, 0, 0, 0.7),
                    0 0 0 6px rgba(245, 48, 3, 0.14);
            }
            .auth-bg {
                background: radial-gradient(circle at top right, #fff5f2, transparent),
                            radial-gradient(circle at bottom left, #fdfcfb, transparent);
                background-color: #fdfdfc;
            }
            .dark .auth-bg {
                background: radial-gradient(circle at top right, #1a100d, transparent),
                            radial-gradient(circle at bottom left, #0a0a0a, transparent);
                background-color: #0a0a0a;
            }
        </style>
    </head>
    <body class="font-sans text-[#1b1b18] dark:text-[#EDEDEC] antialiased auth-bg min-h-screen flex flex-col">
        
        <header class="w-full p-6 lg:px-20 flex justify-between items-center bg-white/50 dark:bg-black/50 backdrop-blur-sm sticky top-0 z-50">
            <div class="text-2xl font-bold tracking-tighter">
                <a href="/" class="hover:opacity-80 transition-opacity flex items-center gap-3">
                    <img src="{{ asset('images/parkmasterlogo.png') }}" alt="ParkMaster Logo" style="width: 34px; height: 34px; object-fit: cover; border-radius: 8px;">
                    <span><span class="text-[#f53003]">Park</span>Master</span>
                </a>
            </div>

            <nav class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium hover:text-[#f53003] transition-colors {{ request()->routeIs('login') ? 'text-[#f53003]' : '' }}">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="px-5 py-2 bg-[#f53003] text-white rounded-sm text-sm font-bold shadow-md hover:shadow-lg hover:opacity-90 transition-all {{ request()->routeIs('register') ? 'ring-2 ring-[#f53003] ring-offset-2' : '' }}">
                    Register
                </a>
            </nav>
        </header>

        <div class="flex-grow flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-6 pb-12">
            <div class="w-full sm:max-w-md mt-6 px-8 py-10 glass-effect rounded-xl overflow-hidden">
                {{ $slot }}
            </div>
        </div>

        <footer class="w-full p-6 text-center border-t border-[#19140015] dark:border-[#3E3E3A15]">
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </footer>
    </body>
</html>
