<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">ParkMaster Systems Control Panel</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="border p-4 rounded bg-gray-50">
                            <p class="text-sm text-gray-600">Total Slots</p>
                            <p class="text-2xl font-bold">{{ $totalSlots ?? 50 }}</p>
                        </div>
                        <div class="border p-4 rounded bg-gray-50">
                            <p class="text-sm text-gray-600">Available</p>
                            <p class="text-2xl font-bold text-green-600">{{ $availableSlots ?? 38 }}</p>
                        </div>
                        <div class="border p-4 rounded bg-gray-50">
                            <p class="text-sm text-gray-600">Occupancy</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $occupancyRate ?? 24 }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>