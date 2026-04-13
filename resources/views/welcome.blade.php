<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'ParkMaster') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] flex flex-col min-h-screen font-sans antialiased">
    
    <header class="w-full p-6 lg:px-20 flex justify-between items-center bg-white/50 dark:bg-black/50 backdrop-blur-sm sticky top-0 z-50">
        <div class="text-2xl font-bold tracking-tighter">
            <span class="text-[#f53003]">Park</span>Master
        </div>

        <nav class="flex items-center gap-2 sm:gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2 border border-[#19140035] dark:border-[#3E3E3A] rounded-sm text-sm font-medium hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-all">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium hover:text-[#f53003] transition-colors">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-5 py-2 bg-[#f53003] text-white rounded-sm text-sm font-bold shadow-md hover:shadow-lg hover:opacity-90 transition-all">
                            Register
                        </a>
                    @endif
                @endauth
            @endif
        </nav>
    </header>

    <main class="flex-grow flex flex-col items-center justify-center text-center px-6">
        <div class="max-w-4xl py-12">
            <h1 class="text-5xl lg:text-7xl font-extrabold mb-6 tracking-tight">
                Parking management <br> made <span class="text-[#f53003]">simple.</span>
            </h1>

            <p class="text-lg lg:text-xl text-[#706f6c] dark:text-[#A1A09A] mb-10 max-w-2xl mx-auto leading-relaxed">
                Streamline your vehicle logs, monitor slots in real-time, and manage your facility with the professional <strong>ParkMaster</strong> dashboard.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-4 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] font-bold rounded-sm shadow-xl hover:scale-105 transition-transform">
                    Get Started
                </a>
                <a href="#about" class="w-full sm:w-auto px-10 py-4 border border-[#19140035] dark:border-[#3E3E3A] font-semibold rounded-sm hover:bg-gray-50 dark:hover:bg-zinc-900 transition-all">
                    Learn More
                </a>
            </div>
        </div>
    </main>

    <footer class="w-full p-10 border-t border-[#19140015] dark:border-[#3E3E3A15] text-center">
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </footer>

</body>
</html>