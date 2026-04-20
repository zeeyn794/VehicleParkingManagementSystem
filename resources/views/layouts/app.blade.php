<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ParkEase — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue-50: #E6F1FB;
            --blue-200: #85B7EB;
            --blue-400: #378ADD;
            --blue-600: #185FA5;
            --blue-800: #0C447C;
            --green-50: #EAF3DE;
            --green-600: #3B6D11;
            --amber-50: #FAEEDA;
            --amber-600: #854F0B;
            --red-50: #FCEBEB;
            --red-600: #A32D2D;
            --gray-50: #F7F7F5;
            --gray-100: #EDEDEA;
            --gray-200: #D3D1C7;
            --gray-400: #888780;
            --gray-600: #5F5E5A;
            --gray-900: #2C2C2A;
            --sidebar-w: 200px;
            --radius-md: 8px;
            --radius-lg: 12px;
        }

        html, body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--gray-50);
            color: var(--gray-900);
            font-size: 14px;
            line-height: 1.6;
        }

        .layout { display: flex; min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            flex-shrink: 0;
            background: #fff;
            border-right: 1px solid var(--gray-100);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            padding: 0 0 1.5rem;
        }

        .sidebar-logo {
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid var(--gray-100);
            margin-bottom: 0.5rem;
        }
        .sidebar-logo .brand { font-size: 15px; font-weight: 600; color: var(--gray-900); letter-spacing: -0.3px; }
        .sidebar-logo .brand span { color: var(--blue-600); }
        .sidebar-logo .tagline { font-size: 11px; color: var(--gray-400); margin-top: 1px; }

        .nav-section-label {
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--gray-400);
            padding: 0.75rem 1.25rem 0.35rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 1.25rem;
            font-size: 13px;
            font-weight: 400;
            color: var(--gray-600);
            text-decoration: none;
            border-left: 2px solid transparent;
            transition: all 0.12s;
            position: relative;
        }
        .nav-item:hover { background: var(--gray-50); color: var(--gray-900); }
        .nav-item.active {
            color: var(--blue-600);
            border-left-color: var(--blue-400);
            background: var(--blue-50);
            font-weight: 500;
        }
        .nav-item svg { width: 15px; height: 15px; flex-shrink: 0; }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.25rem 0;
            border-top: 1px solid var(--gray-100);
        }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 9px;
        }
        .user-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: var(--blue-50);
            color: var(--blue-800);
            font-size: 11px;
            font-weight: 600;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .user-name { font-size: 12px; font-weight: 500; color: var(--gray-900); }
        .user-email { font-size: 11px; color: var(--gray-400); }
        .logout-link { display: block; font-size: 11px; color: var(--red-600); text-decoration: none; margin-top: 0.5rem; }
        .logout-link:hover { text-decoration: underline; }

        /* ── Main content ── */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            padding: 1.75rem 2rem;
            max-width: 100%;
        }

        .page-header {
            margin-bottom: 1.5rem;
        }
        .page-title { font-size: 18px; font-weight: 600; color: var(--gray-900); letter-spacing: -0.3px; }
        .page-subtitle { font-size: 13px; color: var(--gray-400); margin-top: 2px; }

        /* ── Alerts ── */
        .alert { padding: 10px 14px; border-radius: var(--radius-md); font-size: 13px; margin-bottom: 1rem; }
        .alert-success { background: var(--green-50); color: var(--green-600); border: 1px solid #C0DD97; }
        .alert-error { background: var(--red-50); color: var(--red-600); border: 1px solid #F7C1C1; }

    </style>
   @yield('extra-head')
    @stack('head')
</head>
<body>
<div class="layout">

    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="brand">Park<span>Ease</span></div>
            <div class="tagline">Vehicle Management System</div>
        </div>

        <span class="nav-section-label">Menu</span>

        @if (Route::has('user.dashboard'))
        <a href="{{ route('user.dashboard') }}" class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 15 15"><rect x="1" y="1" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.2"/><rect x="9" y="1" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.2"/><rect x="1" y="9" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.2"/><rect x="9" y="9" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.2"/></svg>
            Dashboard
        </a>
        @endif
        @if (Route::has('user.parking'))
        <a href="{{ route('user.parking') }}" class="nav-item {{ request()->routeIs('user.parking') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 15 15"><rect x="1" y="3" width="13" height="9" rx="1.5" stroke="currentColor" stroke-width="1.2"/><path d="M4 3V2a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v1" stroke="currentColor" stroke-width="1.2"/></svg>
            Parking
        </a>
        @endif
        @if (Route::has('user.session'))
        <a href="{{ route('user.session') }}" class="nav-item {{ request()->routeIs('user.session') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 15 15"><circle cx="7.5" cy="7.5" r="6" stroke="currentColor" stroke-width="1.2"/><path d="M7.5 4v4l2.5 1.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
            Active Session
        </a>
        @endif
        @if (Route::has('user.vehicles'))
        <a href="{{ route('user.vehicles') }}" class="nav-item {{ request()->routeIs('user.vehicles*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 16 16"><rect x="1" y="6" width="14" height="6" rx="2" stroke="currentColor" stroke-width="1.2"/><path d="M3 6l2-3h6l2 3" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/><circle cx="4" cy="12" r="1.2" fill="currentColor"/><circle cx="12" cy="12" r="1.2" fill="currentColor"/></svg>
            My Vehicles
        </a>
        @endif
        @if (Route::has('user.history'))
        <a href="{{ route('user.history') }}" class="nav-item {{ request()->routeIs('user.history') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 15 15"><rect x="1" y="2" width="13" height="11" rx="1.5" stroke="currentColor" stroke-width="1.2"/><path d="M1 6h13M5 6v7" stroke="currentColor" stroke-width="1.2"/></svg>
            History
        </a>
        @endif
        @if (Route::has('user.payments'))
        <a href="{{ route('user.payments') }}" class="nav-item {{ request()->routeIs('user.payments*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 15 15"><rect x="1" y="3" width="13" height="9" rx="1.5" stroke="currentColor" stroke-width="1.2"/><path d="M1 7h13" stroke="currentColor" stroke-width="1.2"/></svg>
            Payments
        </a>
        @endif

        <div class="sidebar-footer">
            <div class="user-chip">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-email">{{ Str::limit(auth()->user()->email, 22) }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-link" style="background:none;border:none;cursor:pointer;padding:0;">
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    {{-- Page content --}}
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

</div>
@stack('scripts')
</body>
</html>
