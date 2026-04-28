<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'ParkMaster') }} - Dashboard</title>

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
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 text-sm font-medium hover:text-[#f53003] transition-colors">
                Profile
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 text-sm font-medium hover:text-[#f53003] transition-colors">
                    Log out
                </button>
            </form>
        </nav>
    </header>

    <main class="flex-grow px-6 lg:px-20 py-12">
        <div class="max-w-6xl mx-auto">
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 p-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 p-4 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <h1 class="text-4xl font-bold mb-8">Welcome back, {{ Auth::user()->name }}!</h1>

            <!-- Active Sessions -->
            <div class="bg-white/50 dark:bg-black/50 backdrop-blur-sm rounded-lg p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Active Sessions</h2>
                    <button onclick="openParkingModal()"
                            class="px-6 py-2 bg-[#f53003] text-white rounded-sm text-sm font-medium hover:opacity-90 transition-all">
                        Avail Parking
                    </button>
                </div>
                @if($activeSessions->count() > 0)
                    <div class="space-y-4">
                        @foreach($activeSessions as $session)
                            <div class="p-4 border border-[#19140035] dark:border-[#3E3E3A] rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold">{{ $session->parkingSlot ? $session->parkingSlot->slot_number : 'Unknown' }}</h3>
                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-xs">
                                        Active
                                    </span>
                                </div>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">
                                    Entry: {{ $session->entry_time ? $session->entry_time->format('M j, Y g:i A') : 'N/A' }}
                                </p>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">
                                    Exit: {{ $session->exit_time ? $session->exit_time->format('M j, Y g:i A') : 'N/A' }}
                                </p>
                                <p class="text-sm font-medium">
                                    Total Fee: ${{ number_format($session->total_fee ?? 0, 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">No active parking sessions</p>
                @endif
            </div>

            <!-- Vehicle Profile -->
            <div class="bg-white/50 dark:bg-black/50 backdrop-blur-sm rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">Vehicle Profile</h2>
                @if($userVehicles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($userVehicles as $vehicle)
                            <div class="p-4 border border-[#19140035] dark:border-[#3E3E3A] rounded-lg">
                                <h3 class="font-semibold mb-2">{{ $vehicle->license_plate }}</h3>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    {{ $vehicle->make }} {{ $vehicle->model }}
                                </p>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    Color: {{ $vehicle->color }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">No vehicles registered</p>
                @endif
            </div>

            <div id="parkingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center p-4">
                <div class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg max-w-2xl w-full h-[85vh] shadow-2xl border border-[#19140035] dark:border-[#3E3E3A] overflow-hidden flex flex-col">
                    <div class="flex-shrink-0 flex justify-between items-center p-4 border-b border-[#19140035] dark:border-[#3E3E3A]">
                        <div>
                            <h3 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Parking Checkout</h3>
                            <p class="text-sm md:text-base text-[#706f6c] dark:text-[#A1A09A] mt-2">Review your selection and confirm payment to start parking.</p>
                        </div>
                        <button onclick="closeParkingModal()" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-hidden flex flex-col">
                        <form id="parkingForm" method="POST" action="{{ route('dashboard.park') }}" class="flex-1 flex flex-col">
                            @csrf

                            <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent p-4 space-y-5">
                                <!-- Step 1: Slot Selection -->
                                <div class="mb-4">
                                    <h4 class="text-base md:text-lg font-semibold mb-3 flex items-center text-[#1b1b18] dark:text-[#EDEDEC]">
                                        <span class="bg-[#f53003] text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3">1</span>
                                        Select the parking slot
                                    </h4>
                                    @if($availableSlots->isNotEmpty())
                                        <div>
                                            <label for="slot_id" class="block text-sm md:text-base font-medium mb-3 text-[#1b1b18] dark:text-[#EDEDEC]">Parking slot</label>
                                            <select id="slot_id" name="slot_id" class="w-full p-3 md:p-4 border border-[#19140035] dark:border-[#3E3E3A] rounded bg-white/50 dark:bg-black/50 backdrop-blur-sm text-[#1b1b18] dark:text-[#EDEDEC] text-sm md:text-base" required>
                                                <option value="">Choose a parking slot</option>
                                                @foreach($availableSlots as $slot)
                                                    <option value="{{ $slot->id }}" data-hourly-rate="{{ $slot->hourly_rate }}" data-slot-number="{{ $slot->slot_number }}" data-location="{{ $slot->location }}">
                                                        {{ $slot->slot_number }} - {{ $slot->location }} (${{ number_format($slot->hourly_rate, 2) }}/hour)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="text-center py-8">
                                            <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4">No parking slots available at the moment.</p>
                                            <button type="button" onclick="closeParkingModal()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                                Close
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                @if($availableSlots->isNotEmpty())
                                <!-- Step 2: Vehicle & Duration -->
                                <div class="mb-5">
                                    <h4 class="text-base md:text-lg font-semibold mb-3 flex items-center text-[#1b1b18] dark:text-[#EDEDEC]">
                                        <span class="bg-[#f53003] text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3">2</span>
                                        Vehicle & duration
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="vehicle_id" class="block text-sm md:text-base font-medium mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">Your vehicle</label>
                                            <select id="vehicle_id" name="vehicle_id" class="w-full p-3 md:p-4 border border-[#19140035] dark:border-[#3E3E3A] rounded bg-white/50 dark:bg-black/50 backdrop-blur-sm text-[#1b1b18] dark:text-[#EDEDEC] text-sm md:text-base" required>
                                                <option value="">Choose a vehicle</option>
                                                @foreach($userVehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }} — {{ $vehicle->make }} {{ $vehicle->model }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="duration_hours" class="block text-sm md:text-base font-medium mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">Duration</label>
                                            <select id="duration_hours" name="duration_hours" class="w-full p-3 md:p-4 border border-[#19140035] dark:border-[#3E3E3A] rounded bg-white/50 dark:bg-black/50 backdrop-blur-sm text-[#1b1b18] dark:text-[#EDEDEC] text-sm md:text-base" required>
                                                <option value="">Choose duration</option>
                                                <option value="1">1 hour</option>
                                                <option value="2">2 hours</option>
                                                <option value="3">3 hours</option>
                                                <option value="4">4 hours</option>
                                                <option value="6">6 hours</option>
                                                <option value="8">8 hours</option>
                                                <option value="12">12 hours</option>
                                                <option value="24">24 hours</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Estimate -->
                                <div class="mb-5">
                                    <h4 class="text-base md:text-lg font-semibold mb-3 flex items-center text-[#1b1b18] dark:text-[#EDEDEC]">
                                        <span class="bg-[#f53003] text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3">3</span>
                                        Estimate
                                    </h4>
                                    <div class="bg-white/50 dark:bg-black/50 backdrop-blur-sm rounded-2xl p-4 border border-[#19140035] dark:border-[#3E3E3A]">
                                        <div class="space-y-3">
                                            <div class="flex justify-between">
                                                <span class="text-xs md:text-sm text-[#706f6c] dark:text-[#A1A09A]">Slot</span>
                                                <span id="summary-slot" class="text-xs md:text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Not selected</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs md:text-sm text-[#706f6c] dark:text-[#A1A09A]">Rate</span>
                                                <span id="summary-rate" class="text-xs md:text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">$0.00/hr</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs md:text-sm text-[#706f6c] dark:text-[#A1A09A]">Duration</span>
                                                <span id="summary-duration" class="text-xs md:text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">0 hours</span>
                                            </div>
                                            <hr class="border-[#19140035] dark:border-[#3E3E3A]">
                                            <div class="flex justify-between text-sm md:text-base font-bold">
                                                <span class="text-[#1b1b18] dark:text-[#EDEDEC]">Total</span>
                                                <span id="summary-total" class="text-[#f53003]">$0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms and Confirmation -->
                                <div class="mb-3">
                                    <div class="flex items-start">
                                        <input type="checkbox" id="terms" name="terms" class="mt-0.5 mr-2" required>
                                        <label for="terms" class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                            I agree to the parking terms and conditions. Parking fees are charged upfront and non-refundable.
                                        </label>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </form>

                        @if($availableSlots->isNotEmpty())
                        <div class="flex-shrink-0 border-t border-[#19140035] dark:border-[#3E3E3A] p-4 bg-[#FDFDFC] dark:bg-[#0a0a0a]">
                            <div class="flex gap-3">
                                <button type="button" onclick="closeParkingModal()"
                                        class="flex-1 px-4 py-3 border border-[#19140035] dark:border-[#3E3E3A] rounded text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f53003] hover:text-white hover:border-[#f53003] transition-colors text-sm md:text-base">
                                    Cancel
                                </button>
                                <button type="submit" form="parkingForm" id="confirmBtn"
                                        class="flex-1 px-4 py-3 bg-[#f53003] text-white rounded hover:opacity-90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-sm md:text-base">
                                    Confirm & Pay $0.00
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Post-Checkout Modal -->
    <div id="checkoutModal" class="hidden fixed inset-0 bg-black/70 dark:bg-black/85 z-50 items-center justify-center p-4">
        <div class="bg-[#FDFDFC] dark:bg-[#0a0a0a] rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-hidden shadow-2xl border border-[#19140035]/30 dark:border-[#3E3E3A]/30 flex flex-col h-full">
            <!-- Header with Progress -->
            <div class="flex-shrink-0 bg-gradient-to-r from-[#f53003] to-[#e02a02] p-6 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="4"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold tracking-wide">Complete Your Booking</h2>
                        <button type="button" onclick="closeCheckoutModal()" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-all duration-200 hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-white/20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full w-full"></div>
                        </div>
                        <span class="text-sm font-medium">Step 2 of 2</span>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent">
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column: Booking Summary -->
                    <div class="space-y-6">
                        <div class="bg-gradient-to-br from-white/80 to-white/60 dark:from-black/80 dark:to-black/60 rounded-2xl p-6 border border-[#19140035]/20 dark:border-[#3E3E3A]/20 shadow-lg">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-[#f53003] rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Booking Details</h3>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center p-3 bg-[#f53003]/5 dark:bg-[#f53003]/10 rounded-lg">
                                    <span class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Parking Slot</span>
                                    <span id="checkout-slot" class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC]">—</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-[#f53003]/5 dark:bg-[#f53003]/10 rounded-lg">
                                    <span class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Duration</span>
                                    <span id="checkout-duration" class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC]">—</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-[#f53003]/5 dark:bg-[#f53003]/10 rounded-lg">
                                    <span class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Vehicle</span>
                                    <span id="checkout-vehicle" class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC]">—</span>
                                </div>
                            </div>
                        </div>

                        <!-- Total Amount Card -->
                        <div class="bg-gradient-to-r from-[#f53003] to-[#e02a02] rounded-2xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-white/80 text-sm font-medium mb-1">Total Amount</p>
                                    <p id="checkout-total" class="text-3xl font-bold">$0.00</p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Payment Methods -->
                    <div class="space-y-6">
                        <div class="bg-gradient-to-br from-white/80 to-white/60 dark:from-black/80 dark:to-black/60 rounded-2xl p-6 border border-[#19140035]/20 dark:border-[#3E3E3A]/20 shadow-lg">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-[#f53003] rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Choose Payment</h3>
                            </div>

                            <div class="space-y-3">
                                <label class="flex items-center p-4 border-2 border-[#19140035]/30 dark:border-[#3E3E3A]/30 rounded-xl cursor-pointer hover:border-[#f53003] hover:bg-[#f53003]/5 transition-all duration-200 group">
                                    <input type="radio" name="payment_method" value="card" checked class="mr-4 accent-[#f53003] w-4 h-4">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-8 h-8 bg-gradient-to-br from-[#f53003] to-[#d42a02] rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Credit Card</p>
                                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Visa, MasterCard, Amex</p>
                                        </div>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 border-[#19140035]/30 dark:border-[#3E3E3A]/30 rounded-xl cursor-pointer hover:border-[#f53003] hover:bg-[#f53003]/5 transition-all duration-200 group">
                                    <input type="radio" name="payment_method" value="wallet" class="mr-4 accent-[#f53003] w-4 h-4">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-8 h-8 bg-gradient-to-br from-[#f53003] to-[#d42a02] rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Digital Wallet</p>
                                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">PayPal, Apple Pay, Google Pay</p>
                                        </div>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 border-[#19140035]/30 dark:border-[#3E3E3A]/30 rounded-xl cursor-pointer hover:border-[#f53003] hover:bg-[#f53003]/5 transition-all duration-200 group">
                                    <input type="radio" name="payment_method" value="upi" class="mr-4 accent-[#f53003] w-4 h-4">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-8 h-8 bg-gradient-to-br from-[#f53003] to-[#d42a02] rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Bank Transfer</p>
                                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Direct bank transfer</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Terms Agreement -->
                        <div class="bg-gradient-to-br from-white/60 to-white/40 dark:from-black/60 dark:to-black/40 rounded-xl p-4 border border-[#19140035]/20 dark:border-[#3E3E3A]/20">
                            <label class="flex items-start cursor-pointer group">
                                <input type="checkbox" id="checkout_terms" class="mt-1 mr-3 w-4 h-4 accent-[#f53003] flex-shrink-0">
                                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A] leading-6">
                                    I agree to the <span class="text-[#f53003] font-medium group-hover:underline">parking terms</span> and confirm that payment will be processed immediately. This booking is non-refundable.
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex-shrink-0 border-t border-[#19140035]/10 dark:border-[#3E3E3A]/10 p-6 bg-gradient-to-r from-[#FDFDFC] to-[#F8F8F6] dark:from-[#0a0a0a] dark:to-[#1a1a18] rounded-b-2xl">
                <div class="flex gap-4">
                    <button type="button" onclick="closeCheckoutModal()"
                            class="flex-1 px-6 py-4 border-2 border-[#19140035]/30 dark:border-[#3E3E3A]/30 rounded-xl text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#19140035]/10 dark:hover:bg-[#3E3E3A]/10 hover:border-[#19140035]/50 dark:hover:border-[#3E3E3A]/50 transition-all duration-200 text-sm md:text-base font-semibold">
                        Back
                    </button>
                    <button type="button" onclick="submitCheckout()"
                            class="flex-1 px-6 py-4 bg-gradient-to-r from-[#f53003] to-[#d42a02] hover:from-[#e02a02] hover:to-[#c02502] text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-sm md:text-base font-semibold transform hover:scale-[1.02] active:scale-[0.98]" id="checkoutSubmitBtn">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Process Payment
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedSlot = null;
        let selectedDuration = 1;
        let selectedVehicle = null;

        function openParkingModal() {
            document.getElementById('parkingModal').classList.remove('hidden');
            document.getElementById('parkingModal').classList.add('flex');
            updateOrderSummary();
        }

        function closeParkingModal() {
            document.getElementById('parkingModal').classList.add('hidden');
            document.getElementById('parkingModal').classList.remove('flex');
            // Reset form
            document.getElementById('parkingForm').reset();
            selectedSlot = null;
            selectedDuration = 1;
            selectedVehicle = null;
            updateOrderSummary();
        }

        function openCheckoutModal() {
            // Validate form before opening checkout
            if (!selectedSlot) {
                alert('Please select a parking slot');
                return;
            }
            if (!selectedDuration) {
                alert('Please select a duration');
                return;
            }
            if (!selectedVehicle) {
                alert('Please select a vehicle');
                return;
            }
            if (!document.getElementById('terms').checked) {
                alert('Please agree to the terms and conditions');
                return;
            }

            // Populate checkout modal
            const vehicleSelect = document.getElementById('vehicle_id');
            const vehicleOption = vehicleSelect.options[vehicleSelect.selectedIndex];
            const vehicleText = vehicleOption.text || 'Not selected';

            document.getElementById('checkout-slot').textContent = `${selectedSlot.number} (${selectedSlot.location})`;
            document.getElementById('checkout-duration').textContent = `${selectedDuration} hour${selectedDuration > 1 ? 's' : ''}`;
            document.getElementById('checkout-vehicle').textContent = vehicleText;

            const total = (selectedSlot.rate * selectedDuration).toFixed(2);
            document.getElementById('checkout-total').textContent = `$${total}`;

            document.getElementById('checkoutModal').classList.remove('hidden');
            document.getElementById('checkoutModal').classList.add('flex');
        }

        function closeCheckoutModal() {
            document.getElementById('checkoutModal').classList.add('hidden');
            document.getElementById('checkoutModal').classList.remove('flex');
        }

        function submitCheckout() {
            if (!document.getElementById('checkout_terms').checked) {
                alert('Please confirm the terms to proceed with payment');
                return;
            }

            // Add payment method to form as hidden input and submit
            const form = document.getElementById('parkingForm');
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            // Create hidden input for payment method
            const paymentInput = document.createElement('input');
            paymentInput.type = 'hidden';
            paymentInput.name = 'payment_method';
            paymentInput.value = paymentMethod;
            form.appendChild(paymentInput);

            // Submit the form
            form.submit();
        }

        // Payment method selection styling
        document.addEventListener('DOMContentLoaded', function() {
            const paymentLabels = document.querySelectorAll('input[name="payment_method"]');
            paymentLabels.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove selected styling from all labels
                    document.querySelectorAll('input[name="payment_method"]').forEach(r => {
                        r.closest('label').classList.remove('ring-2', 'ring-[#f53003]', 'bg-[#f53003]/10');
                    });
                    // Add selected styling to current label
                    if (this.checked) {
                        this.closest('label').classList.add('ring-2', 'ring-[#f53003]', 'bg-[#f53003]/10');
                    }
                });
            });

            // Set initial selected state
            const initialSelected = document.querySelector('input[name="payment_method"]:checked');
            if (initialSelected) {
                initialSelected.closest('label').classList.add('ring-2', 'ring-[#f53003]', 'bg-[#f53003]/10');
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('slot_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (this.value) {
                    selectedSlot = {
                        id: this.value,
                        number: selectedOption.getAttribute('data-slot-number'),
                        location: selectedOption.getAttribute('data-location'),
                        rate: parseFloat(selectedOption.getAttribute('data-hourly-rate'))
                    };
                } else {
                    selectedSlot = null;
                }
                updateOrderSummary();
            });

            // Handle vehicle selection
            document.getElementById('vehicle_id').addEventListener('change', function() {
                if (this.value) {
                    selectedVehicle = this.value;
                } else {
                    selectedVehicle = null;
                }
            });

            // Handle duration change
            document.getElementById('duration_hours').addEventListener('change', function() {
                selectedDuration = parseInt(this.value) || 1;
                updateOrderSummary();
            });

            // Prevent form submission and show checkout modal instead
            document.getElementById('parkingForm').addEventListener('submit', function(e) {
                e.preventDefault();
                openCheckoutModal();
            });
        });

        // Close modal when clicking outside
        document.getElementById('parkingModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeParkingModal();
            }
        });

        // Close checkout modal when clicking outside
        document.getElementById('checkoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCheckoutModal();
            }
        });
    </script>
</body>
</html>
