@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Occupancy Overview</h1>
    <p class="text-gray-600">Real-time status of your parking facility.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
        <h3 class="text-gray-500 text-sm font-medium">Total Slots</h3>
        <p class="text-2xl font-bold">150</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
        <h3 class="text-gray-500 text-sm font-medium">Occupied</h3>
        <p class="text-2xl font-bold text-green-600">84</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
        <h3 class="text-gray-500 text-sm font-medium">Available</h3>
        <p class="text-2xl font-bold text-red-600">66</p>
    </div>
</div>
@endsection