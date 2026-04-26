<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkMaster Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <div class="w-64 bg-slate-900 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-slate-800">
                🚗 ParkMaster
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="/admin/occupancy" class="flex items-center p-3 rounded hover:bg-slate-800 transition">
                    <i class="fas fa-chart-pie mr-3"></i> Occupancy Overview
                </a>
                <a href="/admin/slots" class="flex items-center p-3 rounded hover:bg-slate-800 transition">
                    <i class="fas fa-th-large mr-3"></i> Slot Management
                </a>
                <a href="/admin/logs" class="flex items-center p-3 rounded hover:bg-slate-800 transition">
                    <i class="fas fa-history mr-3"></i> Parking Logs
                </a>
                <a href="/admin/users" class="flex items-center p-3 rounded hover:bg-slate-800 transition">
                    <i class="fas fa-users mr-3"></i> User Management
                </a>
            </nav>
            <div class="p-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left p-2 hover:text-red-400">Logout</button>
                </form>
            </div>
        </div>

        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>
</body>
</html>