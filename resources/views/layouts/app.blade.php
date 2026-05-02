<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ParkMaster — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue-50: #E6F1FB;
            --blue-600: #185FA5;
            --blue-400: #378ADD;
            --gray-50: #F7F7F5;
            --gray-100: #EDEDEA;
            --gray-400: #888780;
            --gray-600: #5F5E5A;
            --gray-900: #2C2C2A;
            --sidebar-w: 240px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--gray-50);
            color: var(--gray-900);
            font-size: 14px;
        }

        .layout { display: flex; min-height: 100vh; }

        .sidebar {
            width: var(--sidebar-w);
            background: #fff;
            border-right: 1px solid var(--gray-100);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-100);
        }
        .brand { font-size: 18px; font-weight: 700; }
        .brand span { color: var(--blue-600); }

        .nav-section-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--gray-400);
            padding: 1.5rem 1.5rem 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 1.5rem;
            color: var(--gray-600);
            text-decoration: none;
            transition: 0.2s;
        }
        .nav-item:hover { background: var(--gray-50); color: var(--blue-600); }
        .nav-item.active {
            background: var(--blue-50);
            color: var(--blue-600);
            font-weight: 600;
            border-right: 3px solid var(--blue-600);
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1.5rem;
            border-top: 1px solid var(--gray-100);
        }

        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            padding: 2rem;
        }
    </style>
    @yield('extra-head')
</head>
<body>
<div class="layout">

    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="brand">Park<span>Master</span></div>
            <div style="font-size: 11px; color: var(--gray-400)">Vehicle Management System</div>
        </div>

        <span class="nav-section-label">Menu</span>

        <nav>
            @if(Auth::user()->isAdmin())
                {{-- Admin Links --}}
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Admin Dashboard
                </a>
                <a href="{{ route('admin.occupancy') }}" class="nav-item {{ request()->routeIs('admin.occupancy') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Occupancy Overview
                </a>
                <a href="{{ route('admin.slots') }}" class="nav-item {{ request()->routeIs('admin.slots') ? 'active' : '' }}">
                    <i class="fas fa-parking"></i> Slot Management
                </a>
                <a href="{{ route('admin.logs') }}" class="nav-item {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                    <i class="fas fa-history"></i> Parking Logs
                </a>
                <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> User Management
                </a>
            @else
                {{-- Regular User Links --}}
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="/parking" class="nav-item">
                    <i class="fas fa-car"></i> Parking
                </a>
                <a href="/vehicles" class="nav-item">
                    <i class="fas fa-shuttle-van"></i> My Vehicles
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <div style="width: 32px; height: 32px; background: var(--blue-50); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--blue-600);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size: 13px; font-weight: 600;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 11px; color: var(--gray-400);">{{ Auth::user()->role }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="color: #A32D2D; font-size: 12px; border: none; background: none; cursor: pointer; font-weight: 600;">
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        @isset($header)
            <div style="margin-bottom: 2rem;">
                {{ $header }}
            </div>
        @endisset

        {{-- This handles both @yield('content') and {{ $slot }} --}}
        @yield('content')
        
        @isset($slot)
            {{ $slot }}
        @endisset
    </main>

</div>

@stack('scripts')
</body>
</html>