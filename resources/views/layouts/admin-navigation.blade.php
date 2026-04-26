<div class="p-6">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Park<span class="text-blue-600">Master</span></h2>
        <p class="text-xs text-gray-500 uppercase tracking-wider">Vehicle Management System</p>
    </div>

    <nav class="space-y-4">
        <p class="text-xs font-semibold text-gray-400 uppercase">Menu</p>

        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            <span class="font-medium">Occupancy Overview</span>
        </a>

        <a href="/admin/slots" class="flex items-center space-x-3 p-2 rounded-lg {{ request()->is('admin/slots') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            <span class="font-medium text-sm">Slot Management</span>
        </a>

        <a href="/admin/logs" class="flex items-center space-x-3 p-2 rounded-lg {{ request()->is('admin/logs') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="font-medium text-sm">Parking Logs</span>
        </a>

        <a href="/admin/users" class="flex items-center space-x-3 p-2 rounded-lg {{ request()->is('admin/users') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            <span class="font-medium text-sm">User Management</span>
        </a>
    </nav>

    <div class="mt-auto pt-10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-red-500 hover:text-red-700 text-sm font-bold">Logout</button>
        </form>
    </div>
</div>