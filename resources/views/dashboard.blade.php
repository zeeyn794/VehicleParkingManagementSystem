<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'ParkMaster') }} - Dashboard</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* CSS Variables for Design System - ParkMaster Theme */
        :root {
            --primary-color: #f53003;
            --primary-dark: #d42a02;
            --secondary-color: #22d3ee;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-bg: #0a0a0a;
            --dark-surface: #1C1C1A;
            --light-bg: #FDFDFC;
            --light-surface: #ffffff;
            --text-primary: #1b1b18;
            --text-secondary: #706f6c;
            --text-light: #A1A09A;
            --border-color: #19140035;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Dark Mode Variables - ParkMaster Theme */
        [data-theme="dark"] {
            --light-bg: #0a0a0a;
            --light-surface: #1C1C1A;
            --dark-surface: #2a2a28;
            --text-primary: #EDEDEC;
            --text-secondary: #A1A09A;
            --text-light: #706f6c;
            --border-color: #3E3E3A;
        }

        /* Base Styles - ParkMaster Theme */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--light-bg);
            color: var(--text-primary);
            line-height: 1.6;
            transition: var(--transition);
        }

        /* Layout Container */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transform: translateX(0);
        }

        [data-theme="dark"] .sidebar {
            background: rgba(28, 28, 26, 0.8);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        [data-theme="dark"] .sidebar-header {
            background: rgba(0, 0, 0, 0.3);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        .logo span:first-child {
            color: var(--primary-color);
        }

        .logo i {
            font-size: 2rem;
            color: var(--primary-color);
        }

        .sidebar.collapsed .logo-text {
            display: none;
        }

        .nav-menu {
            flex: 1;
            padding: 1rem 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            gap: 1rem;
        }

        .nav-item:hover {
            background: rgba(245, 48, 3, 0.1);
            color: var(--primary-color);
        }

        .nav-item.active {
            background: rgba(245, 48, 3, 0.1);
            color: var(--primary-color);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary-color);
        }

        .nav-item i {
            width: 20px;
            text-align: center;
        }

        .sidebar.collapsed .nav-text {
            display: none;
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: 280px;
            transition: var(--transition);
        }

        .sidebar.collapsed + .main-content {
            margin-left: 80px;
        }

        /* Header - ParkMaster Theme */
        .header {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        [data-theme="dark"] .header {
            background: rgba(0, 0, 0, 0.5);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .search-bar {
            position: relative;
            width: 300px;
        }

        .search-bar input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.125rem;
            background: var(--light-bg);
            color: var(--text-primary);
            transition: var(--transition);
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(245, 48, 3, 0.1);
        }

        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.125rem;
            transition: var(--transition);
        }

        .notification-btn:hover {
            background: rgba(245, 48, 3, 0.1);
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            width: 8px;
            height: 8px;
            background: var(--danger-color);
            border-radius: 50%;
        }

        .theme-toggle {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.125rem;
            transition: var(--transition);
        }

        .theme-toggle:hover {
            background: rgba(245, 48, 3, 0.1);
            color: var(--primary-color);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 0.125rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .user-profile:hover {
            background: rgba(245, 48, 3, 0.1);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .user-status {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* Dashboard Content */
        .dashboard-content {
            padding: 2rem;
        }

        /* User Overview Panel */
        .overview-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .overview-card {
            background: var(--light-surface);
            border-radius: 0.125rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .overview-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .overview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .overview-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .overview-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.125rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .overview-icon.icon-primary {
            background: rgba(245, 48, 3, 0.1);
            color: var(--primary-color);
        }

        .icon-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .icon-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .overview-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .overview-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .parking-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-parked {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .status-available {
            background: rgba(245, 48, 3, 0.1);
            color: var(--primary-color);
        }

        /* Parking Grid */
        .parking-section {
            background: var(--light-surface);
            border-radius: 0.125rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .parking-stats {
            display: flex;
            gap: 2rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .stat-dot.available {
            background: var(--success-color);
        }

        .stat-dot.occupied {
            background: var(--danger-color);
        }

        .stat-dot.reserved {
            background: var(--warning-color);
        }

        .parking-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .parking-slot {
            aspect-ratio: 1;
            border-radius: 0.125rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            border: 2px solid transparent;
        }

        .parking-slot:hover {
            transform: scale(1.05);
        }

        .slot-available {
            background: rgba(16, 185, 129, 0.1);
            border-color: var(--success-color);
            color: var(--success-color);
        }

        .slot-occupied {
            background: rgba(239, 68, 68, 0.1);
            border-color: var(--danger-color);
            color: var(--danger-color);
            cursor: not-allowed;
        }

        .slot-reserved {
            background: rgba(245, 158, 11, 0.1);
            border-color: var(--warning-color);
            color: var(--warning-color);
        }

        .slot-number {
            font-weight: 700;
            font-size: 1.125rem;
        }

        .slot-type {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .slot-indicator {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        /* Timer Section */
        .timer-section {
            background: var(--light-surface);
            border-radius: 0.125rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
        }

        .timer-display {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 0.125rem;
            color: white;
        }

        .timer-value {
            font-size: 3rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            margin-bottom: 0.5rem;
        }

        .timer-label {
            font-size: 1.125rem;
            opacity: 0.9;
        }

        .billing-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .billing-item {
            text-align: center;
            padding: 1rem;
            background: rgba(245, 48, 3, 0.05);
            border-radius: 0.125rem;
        }

        .billing-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .billing-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* History Section */
        .history-section {
            background: var(--light-surface);
            border-radius: 0.125rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
        }

        .history-controls {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 200px;
            padding: 0.625rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.125rem;
            background: var(--light-bg);
            color: var(--text-primary);
        }

        .filter-select {
            padding: 0.625rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.125rem;
            background: var(--light-bg);
            color: var(--text-primary);
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table th {
            text-align: left;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-secondary);
        }

        .history-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .history-table tr:hover {
            background: rgba(245, 48, 3, 0.05);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--light-surface);
            border-radius: 0.125rem;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.25rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.125rem;
            background: var(--light-bg);
            color: var(--text-primary);
            transition: var(--transition);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(245, 48, 3, 0.1);
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .payment-method {
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 0.125rem;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .payment-method:hover {
            border-color: var(--primary-color);
        }

        .payment-method.selected {
            border-color: var(--primary-color);
            background: rgba(245, 48, 3, 0.1);
        }

        .payment-method i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.125rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: var(--text-secondary);
            color: white;
        }

        .btn-secondary:hover {
            background: var(--text-primary);
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-block {
            width: 100%;
            justify-content: center;
        }

        /* Notifications */
        .notification-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 3000;
            max-width: 400px;
        }

        .notification {
            background: var(--light-surface);
            border-radius: 0.125rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-lg);
            border-left: 4px solid var(--primary-color);
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .notification.success {
            border-left-color: var(--success-color);
        }

        .notification.warning {
            border-left-color: var(--warning-color);
        }

        .notification.error {
            border-left-color: var(--danger-color);
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .notification-title {
            font-weight: 600;
            color: var(--text-primary);
        }

        .notification-close {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .notification-message {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .search-bar {
                width: 200px;
            }

            .user-info {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .dashboard-content {
                padding: 1rem;
            }

            .overview-section {
                grid-template-columns: 1fr;
            }

            .parking-grid {
                grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
            }

            .billing-info {
                grid-template-columns: 1fr;
            }

            .timer-value {
                font-size: 2rem;
            }

            .history-controls {
                flex-direction: column;
            }

            .search-input {
                width: 100%;
            }

            .modal-content {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 1rem;
            }

            .header-right {
                gap: 0.5rem;
            }

            .parking-stats {
                flex-direction: column;
                gap: 1rem;
            }

            .btn-group {
                flex-direction: column;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(245, 48, 3, 0.3);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Page Sections */
        .page-section {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-section.hidden {
            display: none;
        }

        /* Utility Classes */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .hidden { display: none !important; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-1 { gap: 0.25rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-4 { gap: 1rem; }
        .mt-4 { margin-top: 1rem; }
        .mb-4 { margin-bottom: 1rem; }
        .p-4 { padding: 1rem; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-parking"></i>
                    <span class="logo-text"><span>Park</span>Master</span>
                </div>
            </div>
            <nav class="nav-menu">
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span class="nav-text">Admin Dashboard</span>
                    </a>
                    <a href="{{ route('admin.occupancy') }}" class="nav-item {{ request()->routeIs('admin.occupancy') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span class="nav-text">Occupancy Overview</span>
                    </a>
                    <a href="{{ route('admin.slots') }}" class="nav-item {{ request()->routeIs('admin.slots') ? 'active' : '' }}">
                        <i class="fas fa-parking"></i>
                        <span class="nav-text">Slot Management</span>
                    </a>
                    <a href="{{ route('admin.logs') }}" class="nav-item {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span class="nav-text">Parking Logs</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">User Management</span>
                    </a>
                @else
                    <a href="#" class="nav-item active" data-page="dashboard">
                        <i class="fas fa-home"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                    <a href="#" class="nav-item" data-page="parking">
                        <i class="fas fa-car"></i>
                        <span class="nav-text">My Parking</span>
                    </a>
                    <a href="#" class="nav-item" data-page="history">
                        <i class="fas fa-history"></i>
                        <span class="nav-text">History</span>
                    </a>
                    <a href="#" class="nav-item" data-page="vehicles">
                        <i class="fas fa-car-side"></i>
                        <span class="nav-text">Vehicles</span>
                    </a>
                    <a href="#" class="nav-item" data-page="payments">
                        <i class="fas fa-credit-card"></i>
                        <span class="nav-text">Payments</span>
                    </a>
                    <a href="#" class="nav-item" data-page="profile">
                        <i class="fas fa-user"></i>
                        <span class="nav-text">Profile</span>
                    </a>
                    <a href="#" class="nav-item" data-page="settings">
                        <i class="fas fa-cog"></i>
                        <span class="nav-text">Settings</span>
                    </a>
                @endif
            </nav>
            <div class="sidebar-footer p-4">
                <button class="btn btn-danger btn-block" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">Logout</span>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search parking slots, history..." id="searchInput">
                    </div>
                </div>
                <div class="header-right">
                    <button class="notification-btn" onclick="showNotifications()">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge"></span>
                    </button>
                    <button class="theme-toggle" onclick="toggleTheme()">
                        <i class="fas fa-moon" id="themeIcon"></i>
                    </button>
                    <div class="user-profile">
                        <div class="user-avatar">{{ substr($user->name, 0, 2) }}</div>
                        <div class="user-info">
                            <div class="user-name">{{ $user->name }}</div>
                            <div class="user-status">{{ ucfirst($user->role) }} Member</div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">

                <!-- DASHBOARD PAGE -->
                <section class="page-section" id="page-dashboard">
                <!-- User Overview Panel -->
                <section class="overview-section">
                    <div class="overview-card">
                        <div class="overview-header">
                            <div>
                                <div class="overview-title">Account Status</div>
                                <div class="parking-status status-available">
                                    <i class="fas fa-circle"></i>
                                    Available
                                </div>
                            </div>
                            <div class="overview-icon icon-primary">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="overview-value">{{ $user->name }}</div>
                        <div class="overview-label">{{ ucfirst($user->role) }} Member Since {{ $user->created_at->format('Y') }}</div>
                    </div>

                    <div class="overview-card">
                        <div class="overview-header">
                            <div>
                                <div class="overview-title">Current Parking</div>
                                @if($activeSessions->count() > 0)
                                    <div class="parking-status status-parked">
                                        <i class="fas fa-circle"></i>
                                        Parked
                                    </div>
                                @else
                                    <div class="parking-status status-available">
                                        <i class="fas fa-circle"></i>
                                        Available
                                    </div>
                                @endif
                            </div>
                            <div class="overview-icon icon-success">
                                <i class="fas fa-car"></i>
                            </div>
                        </div>
                        @if($activeSessions->count() > 0)
                            @php $session = $activeSessions->first(); @endphp
                            <div class="overview-value">{{ $session->parkingSlot->slot_number }}</div>
                            <div class="overview-label">Slot {{ $session->parkingSlot->slot_number }} • Active</div>
                        @else
                            <div class="overview-value">No Active</div>
                            <div class="overview-label">Not Currently Parked</div>
                        @endif
                    </div>

                    <div class="overview-card">
                        <div class="overview-header">
                            <div>
                                <div class="overview-title">Total Spent</div>
                            </div>
                            <div class="overview-icon icon-warning">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                        <div class="overview-value">${{ number_format($totalSpent, 2) }}</div>
                        <div class="overview-label">{{ $totalSessions }} Sessions Total</div>
                    </div>
                </section>

                <!-- Real-Time Parking Availability -->
                <section class="parking-section">
                    <div class="section-header">
                        <h2 class="section-title">Parking Availability</h2>
                        <div class="parking-stats">
                            <div class="stat-item">
                                <div class="stat-dot available"></div>
                                <span>12 Available</span>
                            </div>
                            <div class="stat-item">
                                <div class="stat-dot occupied"></div>
                                <span>8 Occupied</span>
                            </div>
                            <div class="stat-item">
                                <div class="stat-dot reserved"></div>
                                <span>4 Reserved</span>
                            </div>
                        </div>
                    </div>
                    <div class="parking-grid" id="parkingGrid">
                        <!-- Parking slots will be generated by JavaScript -->
                    </div>
                    <button class="btn btn-primary" onclick="refreshParkingGrid()">
                        <i class="fas fa-sync-alt"></i>
                        Refresh Status
                    </button>
                </section>

                <!-- Live Timer & Billing -->
                <section class="timer-section" id="timerSection">
                    <div class="section-header">
                        <h2 class="section-title">Current Session</h2>
                    </div>
                    <div class="timer-display">
                        <div class="timer-value" id="timerDisplay">02:15:30</div>
                        <div class="timer-label">Parking Duration</div>
                    </div>
                    <div class="billing-info">
                        <div class="billing-item">
                            <div class="billing-value">$6.25</div>
                            <div class="billing-label">Current Fee</div>
                        </div>
                        <div class="billing-item">
                            <div class="billing-value">$3.00</div>
                            <div class="billing-label">Hourly Rate</div>
                        </div>
                        <div class="billing-item">
                            <div class="billing-value">A-15</div>
                            <div class="billing-label">Slot Number</div>
                        </div>
                        <div class="billing-item">
                            <div class="billing-value">ABC-123</div>
                            <div class="billing-label">License Plate</div>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-success btn-block" onclick="extendParking()">
                            <i class="fas fa-plus"></i>
                            Extend Time
                        </button>
                        <button class="btn btn-danger btn-block" onclick="endParking()">
                            <i class="fas fa-stop"></i>
                            End Session
                        </button>
                    </div>
                </section>

                <!-- Parking History -->
                <section class="history-section">
                    <div class="section-header">
                        <h2 class="section-title">Parking History</h2>
                    </div>
                    <div class="history-controls">
                        <input type="text" class="search-input" placeholder="Search history..." id="historySearch">
                        <select class="filter-select" id="historyFilter">
                            <option value="">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select>
                        <button class="btn btn-secondary" onclick="exportHistory()">
                            <i class="fas fa-download"></i>
                            Export
                        </button>
                    </div>
                    <div class="table-container">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Slot</th>
                                    <th>Duration</th>
                                    <th>Vehicle</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                <!-- History rows will be generated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </section>
                </section><!-- End Dashboard Page -->

                <!-- MY PARKING PAGE -->
                <section class="page-section hidden" id="page-parking">
                    <div class="parking-section">
                        <div class="section-header">
                            <h2 class="section-title">My Active Parking Sessions</h2>
                        </div>
                        @if($activeSessions->count() > 0)
                            @foreach($activeSessions as $session)
                            <div class="overview-card" style="margin-bottom: 1rem;">
                                <div class="overview-header">
                                    <div>
                                        <div class="overview-title">Slot {{ $session->parkingSlot->slot_number }}</div>
                                        <div class="parking-status status-parked">
                                            <i class="fas fa-circle"></i>
                                            Active
                                        </div>
                                    </div>
                                    <div class="overview-icon icon-success">
                                        <i class="fas fa-car"></i>
                                    </div>
                                </div>
                                <div class="overview-value">{{ $session->vehicle->license_plate }}</div>
                                <div class="overview-label">
                                    Entry: {{ $session->entry_time->format('M d, Y H:i') }}<br>
                                    Exit: {{ $session->exit_time->format('M d, Y H:i') }}<br>
                                    Fee: ${{ number_format($session->total_fee, 2) }}
                                </div>
                                <div class="btn-group" style="margin-top: 1rem;">
                                    <button class="btn btn-success" onclick="extendSession({{ $session->id }})">
                                        <i class="fas fa-plus"></i>
                                        Extend
                                    </button>
                                    <button class="btn btn-danger" onclick="endSession({{ $session->id }})">
                                        <i class="fas fa-stop"></i>
                                        End Session
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                <i class="fas fa-parking" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                                <p>No active parking sessions</p>
                                <button class="btn btn-primary" onclick="showPage('dashboard')" style="margin-top: 1rem;">
                                    Book a Slot
                                </button>
                            </div>
                        @endif
                    </div>
                </section>

                <!-- HISTORY PAGE -->
                <section class="page-section hidden" id="page-history">
                    <div class="parking-section">
                        <div class="section-header">
                            <h2 class="section-title">Full Parking History</h2>
                        </div>
                        <div class="history-controls">
                            <input type="text" class="search-input" placeholder="Search by vehicle or slot..." id="fullHistorySearch">
                            <select class="filter-select" id="fullHistoryFilter">
                                <option value="">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                            </select>
                            <button class="btn btn-secondary" onclick="exportFullHistory()">
                                <i class="fas fa-download"></i>
                                Export CSV
                            </button>
                        </div>
                        <div class="table-container">
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Slot</th>
                                        <th>Vehicle</th>
                                        <th>Entry Time</th>
                                        <th>Exit Time</th>
                                        <th>Duration</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="fullHistoryTableBody">
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                            <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                                            <p>Enter a search term or select a filter to view your full parking history</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- VEHICLES PAGE -->
                <section class="page-section hidden" id="page-vehicles">
                    <div class="parking-section">
                        <div class="section-header">
                            <h2 class="section-title">My Vehicles</h2>
                            <button class="btn btn-primary" onclick="openModal('addVehicleModal')">
                                <i class="fas fa-plus"></i>
                                Add Vehicle
                            </button>
                        </div>
                        <div id="vehiclesGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                            @forelse($userVehicles as $vehicle)
                            <div class="overview-card">
                                <div class="overview-header">
                                    <div>
                                        <div class="overview-title">{{ $vehicle->license_plate }}</div>
                                        <div class="parking-status" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                                            <i class="fas fa-check-circle"></i>
                                            Active
                                        </div>
                                    </div>
                                    <div class="overview-icon icon-primary">
                                        <i class="fas fa-car-side"></i>
                                    </div>
                                </div>
                                <div class="overview-value">{{ $vehicle->make }} {{ $vehicle->model }}</div>
                                <div class="overview-label">Color: {{ $vehicle->color ?? 'N/A' }}</div>
                            </div>
                            @empty
                            <div id="noVehiclesMessage" style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-secondary);">
                                <i class="fas fa-car" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                <p>No vehicles added yet</p>
                                <p style="font-size: 0.875rem; margin-top: 0.5rem;">Click "Add Vehicle" to get started</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </section>

                <!-- PAYMENTS PAGE -->
                <section class="page-section hidden" id="page-payments">
                    <div class="parking-section">
                        <div class="section-header">
                            <h2 class="section-title">Payment Methods</h2>
                            <button class="btn btn-primary" onclick="openModal('addPaymentModal')">
                                <i class="fas fa-plus"></i>
                                Add Payment Method
                            </button>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                            <div class="overview-card" style="cursor: pointer;" onclick="showNotification('Card payment selected', 'info')">
                                <div class="overview-header">
                                    <div class="overview-icon icon-primary">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                </div>
                                <div class="overview-value" style="font-size: 1.25rem;">Credit Card</div>
                                <div class="overview-label">**** **** **** 4242</div>
                            </div>
                            <div class="overview-card" style="cursor: pointer;" onclick="showNotification('E-Wallet selected', 'info')">
                                <div class="overview-header">
                                    <div class="overview-icon icon-warning">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                </div>
                                <div class="overview-value" style="font-size: 1.25rem;">E-Wallet</div>
                                <div class="overview-label">Balance: $45.50</div>
                            </div>
                            <div class="overview-card" style="cursor: pointer;" onclick="showNotification('Cash payment selected', 'info')">
                                <div class="overview-header">
                                    <div class="overview-icon icon-success">
                                        <i class="fas fa-money-bill"></i>
                                    </div>
                                </div>
                                <div class="overview-value" style="font-size: 1.25rem;">Cash</div>
                                <div class="overview-label">Pay at exit</div>
                            </div>
                        </div>

                        <div class="section-header">
                            <h2 class="section-title">Payment History</h2>
                        </div>
                        <div class="table-container">
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                            <p>No payment records yet</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- PROFILE PAGE -->
                <section class="page-section hidden" id="page-profile">
                    <div class="parking-section">
                        <div class="section-header">
                            <h2 class="section-title">My Profile</h2>
                        </div>
                        <div style="max-width: 600px;">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-input" value="{{ $user->name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-input" value="{{ ucfirst($user->role) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Member Since</label>
                                <input type="text" class="form-input" value="{{ $user->created_at->format('F Y') }}" readonly>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i>
                                    Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SETTINGS PAGE -->
                <section class="page-section hidden" id="page-settings">
                    <div class="parking-section">
                        <div class="section-header">
                            <h2 class="section-title">Settings</h2>
                        </div>
                        <div style="max-width: 600px;">
                            <div class="overview-card" style="margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-weight: 600; margin-bottom: 0.25rem;">Dark Mode</div>
                                        <div style="color: var(--text-secondary); font-size: 0.875rem;">Toggle dark theme</div>
                                    </div>
                                    <button class="btn btn-secondary" onclick="toggleTheme()">
                                        <i class="fas fa-moon" id="settingsThemeIcon"></i>
                                        Toggle
                                    </button>
                                </div>
                            </div>
                            <div class="overview-card" style="margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-weight: 600; margin-bottom: 0.25rem;">Notifications</div>
                                        <div style="color: var(--text-secondary); font-size: 0.875rem;">Enable push notifications</div>
                                    </div>
                                    <button class="btn btn-success" onclick="showNotification('Notifications enabled!', 'success')">
                                        <i class="fas fa-bell"></i>
                                        Enabled
                                    </button>
                                </div>
                            </div>
                            <div class="overview-card" style="margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-weight: 600; margin-bottom: 0.25rem; color: var(--danger-color);">Delete Account</div>
                                        <div style="color: var(--text-secondary); font-size: 0.875rem;">Permanently delete your account</div>
                                    </div>
                                    <button class="btn btn-danger" onclick="showNotification('Please contact admin to delete account', 'warning')">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </main>
    </div>

    <!-- Booking Modal -->
    <div class="modal" id="bookingModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Book Parking Slot</h3>
                <button class="modal-close" onclick="closeModal('bookingModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="bookingForm">
                <div class="form-group">
                    <label class="form-label">Selected Slot</label>
                    <input type="text" class="form-input" id="selectedSlotInput" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Vehicle</label>
                    <select class="form-input" id="vehicleSelect" required>
                        <option value="">Select Vehicle</option>
                        @foreach($userVehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }} - {{ $vehicle->make }} {{ $vehicle->model }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Duration</label>
                    <select class="form-input" id="durationSelect" required>
                        <option value="">Select Duration</option>
                        <option value="1">1 Hour - $3.00</option>
                        <option value="2">2 Hours - $6.00</option>
                        <option value="4">4 Hours - $12.00</option>
                        <option value="8">8 Hours - $24.00</option>
                        <option value="24">24 Hours - $72.00</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Estimated Cost</label>
                    <input type="text" class="form-input" id="estimatedCost" readonly value="$0.00">
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('bookingModal')">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-credit-card"></i>
                        Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Payment Details</h3>
                <button class="modal-close" onclick="closeModal('paymentModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="paymentForm">
                <div class="form-group">
                    <label class="form-label">Booking Summary</label>
                    <div style="background: var(--light-bg); padding: 1rem; border-radius: 0.5rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Slot:</span>
                            <strong id="paymentSlot">A-15</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Duration:</span>
                            <strong id="paymentDuration">2 Hours</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Total:</span>
                            <strong id="paymentTotal" style="color: var(--primary-color);">$6.00</strong>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Payment Method</label>
                    <div class="payment-methods">
                        <div class="payment-method" data-method="card" onclick="selectPaymentMethod('card')">
                            <i class="fas fa-credit-card"></i>
                            <div>Card</div>
                        </div>
                        <div class="payment-method" data-method="wallet" onclick="selectPaymentMethod('wallet')">
                            <i class="fas fa-wallet"></i>
                            <div>E-Wallet</div>
                        </div>
                        <div class="payment-method" data-method="cash" onclick="selectPaymentMethod('cash')">
                            <i class="fas fa-money-bill"></i>
                            <div>Cash</div>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="cardDetails" style="display: none;">
                    <label class="form-label">Card Number</label>
                    <input type="text" class="form-input" placeholder="1234 5678 9012 3456" maxlength="19">
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('paymentModal')">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i>
                        Complete Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Vehicle Modal -->
    <div class="modal" id="addVehicleModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add New Vehicle</h3>
                <button class="modal-close" onclick="closeModal('addVehicleModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addVehicleForm">
                <div class="form-group">
                    <label class="form-label">Vehicle Type *</label>
                    <select class="form-input" id="vehicleType" required>
                        <option value="">Select Type</option>
                        <option value="car">Car</option>
                        <option value="motorcycle">Motorcycle</option>
                        <option value="truck">Truck</option>
                        <option value="suv">SUV</option>
                        <option value="van">Van</option>
                        <option value="electric">Electric Vehicle</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">License Plate *</label>
                    <input type="text" class="form-input" id="licensePlate" placeholder="ABC-1234" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Make</label>
                    <input type="text" class="form-input" id="vehicleMake" placeholder="Toyota, Honda, Ford...">
                </div>
                <div class="form-group">
                    <label class="form-label">Model</label>
                    <input type="text" class="form-input" id="vehicleModel" placeholder="Camry, Civic, F-150...">
                </div>
                <div class="form-group">
                    <label class="form-label">Color</label>
                    <input type="text" class="form-input" id="vehicleColor" placeholder="Red, Blue, Black...">
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addVehicleModal')">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Add Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Payment Method Modal -->
    <div class="modal" id="addPaymentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add Payment Method</h3>
                <button class="modal-close" onclick="closeModal('addPaymentModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addPaymentForm">
                <div class="form-group">
                    <label class="form-label">Payment Type *</label>
                    <select class="form-input" id="paymentType" required>
                        <option value="">Select Payment Type</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="ewallet">E-Wallet (PayPal, GCash, etc.)</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                <div id="cardPaymentFields" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">Card Number *</label>
                        <input type="text" class="form-input" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cardholder Name *</label>
                        <input type="text" class="form-input" id="cardHolder" placeholder="John Doe">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Expiry Date *</label>
                            <input type="text" class="form-input" id="expiryDate" placeholder="MM/YY" maxlength="5">
                        </div>
                        <div class="form-group">
                            <label class="form-label">CVV *</label>
                            <input type="text" class="form-input" id="cvv" placeholder="123" maxlength="4">
                        </div>
                    </div>
                </div>
                <div id="ewalletFields" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">E-Wallet Provider *</label>
                        <select class="form-input" id="ewalletProvider">
                            <option value="">Select Provider</option>
                            <option value="paypal">PayPal</option>
                            <option value="gcash">GCash</option>
                            <option value="paymaya">PayMaya</option>
                            <option value="grabpay">GrabPay</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account Number/Email *</label>
                        <input type="text" class="form-input" id="ewalletAccount" placeholder="Enter account details">
                    </div>
                </div>
                <div id="bankFields" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">Bank Name *</label>
                        <input type="text" class="form-input" id="bankName" placeholder="Bank of America, Chase...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account Number *</label>
                        <input type="text" class="form-input" id="bankAccount" placeholder="Enter account number">
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addPaymentModal')">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Add Payment Method
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- First Payment Setup Modal (for new users) -->
    <div class="modal" id="firstPaymentSetupModal">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-color), #ff6b35);">
                <h3 class="modal-title" style="color: white;">
                    <i class="fas fa-credit-card"></i>
                    Welcome! Set Up Your Payment Method
                </h3>
            </div>
            <div style="padding: 1.5rem;">
                <p style="margin-bottom: 1.5rem; color: var(--text-secondary);">
                    To start booking parking slots, you need to add a payment method first. This will be used for all your parking transactions.
                </p>
                <form id="firstPaymentForm">
                    <div class="form-group">
                        <label class="form-label">Payment Type *</label>
                        <select class="form-input" id="firstPaymentType" required>
                            <option value="">Select Payment Type</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="ewallet">E-Wallet</option>
                            <option value="bank">Bank Transfer</option>
                        </select>
                    </div>
                    <div id="firstCardFields" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">Card Number *</label>
                            <input type="text" class="form-input" id="firstCardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cardholder Name *</label>
                            <input type="text" class="form-input" id="firstCardHolder" placeholder="John Doe">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Expiry Date *</label>
                                <input type="text" class="form-input" id="firstExpiryDate" placeholder="MM/YY" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label class="form-label">CVV *</label>
                                <input type="text" class="form-input" id="firstCvv" placeholder="123" maxlength="4">
                            </div>
                        </div>
                    </div>
                    <div id="firstEwalletFields" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">E-Wallet Provider *</label>
                            <select class="form-input" id="firstEwalletProvider">
                                <option value="">Select Provider</option>
                                <option value="paypal">PayPal</option>
                                <option value="gcash">GCash</option>
                                <option value="paymaya">PayMaya</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Account Number/Email *</label>
                            <input type="text" class="form-input" id="firstEwalletAccount" placeholder="Enter account details">
                        </div>
                    </div>
                    <div class="btn-group" style="margin-top: 1.5rem;">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-check"></i>
                            Save Payment Method
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer"></div>

    <script>
        // Global State Management
        const state = {
            currentUser: {
                name: '{{ $user->name }}',
                email: '{{ $user->email }}',
                role: '{{ $user->role }}',
                balance: {{ $totalSpent }},
                status: '{{ $activeSessions->count() > 0 ? 'parked' : 'available' }}',
                currentParking: {{ $activeSessions->count() > 0 ? "'" . $activeSessions->first()->parkingSlot->slot_number . "'" : 'null' }}
            },
            parkingSlots: @json($allParkingSlots),
            currentSession: @json($activeSessions->first() ?? null),
            timer: null,
            selectedSlot: null,
            selectedPaymentMethod: null,
            notifications: [],
            history: [],
            historyLoaded: false
        };

        // Initialize Dashboard
        document.addEventListener('DOMContentLoaded', function() {
            initializeDashboard();
            loadParkingData();
            loadHistoryData();
            startTimer();
            setupEventListeners();
        });

        function initializeDashboard() {
            // Load theme preference
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                updateThemeIcon();
            }

            // Generate parking grid
            generateParkingGrid();
        }

        function generateParkingGrid() {
            const grid = document.getElementById('parkingGrid');
            grid.innerHTML = '';

            // Use real parking slot data from database
            state.parkingSlots.forEach((slotData, index) => {
                const slot = document.createElement('div');
                const slotNumber = slotData.slot_number || slotData.slot_code;
                const status = slotData.status || 'available';
                
                slot.className = `parking-slot slot-${status}`;
                slot.dataset.slot = slotNumber;
                slot.dataset.status = status;
                slot.dataset.slotId = slotData.id;
                
                slot.innerHTML = `
                    <div class="slot-indicator" style="background: ${getStatusColor(status)}"></div>
                    <div class="slot-number">${slotNumber}</div>
                    <div class="slot-type">${slotData.type || 'Standard'}</div>
                `;
                
                if (status === 'available') {
                    slot.onclick = () => selectSlot(slotNumber, slotData.id);
                } else if (status === 'occupied') {
                    slot.onclick = () => showNotification(`Slot ${slotNumber} is currently occupied`, 'warning');
                }
                
                grid.appendChild(slot);
            });
        }

        function getRandomSlotStatus() {
            const statuses = ['available', 'occupied', 'reserved'];
            const weights = [0.5, 0.35, 0.15]; // 50% available, 35% occupied, 15% reserved
            const random = Math.random();
            let cumulative = 0;
            
            for (let i = 0; i < weights.length; i++) {
                cumulative += weights[i];
                if (random < cumulative) {
                    return statuses[i];
                }
            }
            return 'available';
        }

        function getStatusColor(status) {
            const colors = {
                available: 'var(--success-color)',
                occupied: 'var(--danger-color)',
                reserved: 'var(--warning-color)'
            };
            return colors[status] || 'var(--text-secondary)';
        }

        function getSlotType(index) {
            const types = ['Standard', 'Compact', 'EV', 'Disabled'];
            return types[index % types.length];
        }

        function selectSlot(slotNumber, slotId) {
            state.selectedSlot = { number: slotNumber, id: slotId };
            document.getElementById('selectedSlotInput').value = slotNumber;
            openModal('bookingModal');
        }

        function loadParkingData() {
            // Simulate loading parking data
            setTimeout(() => {
                updateParkingStats();
            }, 1000);
        }

        function updateParkingStats() {
            const slots = document.querySelectorAll('.parking-slot');
            const stats = {
                available: 0,
                occupied: 0,
                reserved: 0
            };

            slots.forEach(slot => {
                const status = slot.dataset.status;
                stats[status]++;
            });

            // Update stats display
            const statElements = document.querySelectorAll('.stat-item span');
            statElements[0].textContent = `${stats.available} Available`;
            statElements[1].textContent = `${stats.occupied} Occupied`;
            statElements[2].textContent = `${stats.reserved} Reserved`;
        }

        function loadHistoryData() {
            // Initially empty - will be loaded when user searches
            state.history = [];
            renderHistoryTable();
        }

        function renderHistoryTable() {
            const tbody = document.getElementById('historyTableBody');
            tbody.innerHTML = '';

            if (!state.historyLoaded || state.history.length === 0) {
                // Show empty state message
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                        <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                        <p>Enter a search term or select a filter to view your parking history</p>
                    </td>
                `;
                tbody.appendChild(row);
                return;
            }

            state.history.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.date}</td>
                    <td>${record.slot}</td>
                    <td>${record.duration}</td>
                    <td>${record.vehicle}</td>
                    <td>${record.amount}</td>
                    <td><span class="parking-status status-completed">${record.status}</span></td>
                `;
                tbody.appendChild(row);
            });
        }

        function startTimer() {
            // If no active session, hide timer section
            if (!state.currentSession) {
                document.getElementById('timerSection').style.display = 'none';
                return;
            }
            
            document.getElementById('timerSection').style.display = 'block';
            
            // Get exit time from current session
            const exitTime = new Date(state.currentSession.exit_time);
            
            state.timer = setInterval(() => {
                const now = new Date();
                const diff = exitTime - now;
                
                if (diff <= 0) {
                    // Time's up
                    clearInterval(state.timer);
                    document.getElementById('timerDisplay').textContent = '00:00:00';
                    showNotification('Your parking session has expired!', 'warning');
                    return;
                }
                
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const secs = Math.floor((diff % (1000 * 60)) / 1000);
                
                const display = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                document.getElementById('timerDisplay').textContent = display;
                
                // Update billing info
                updateBillingInfo();
            }, 1000);
        }

        function updateBillingInfo() {
            // Show current fee based on session
            if (state.currentSession) {
                const totalFee = parseFloat(state.currentSession.total_fee).toFixed(2);
                document.querySelector('.billing-value').textContent = `$${totalFee}`;
            }
        }

        function setupEventListeners() {
            // Navigation
            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    const page = this.dataset.page;
                    if (page) {
                        e.preventDefault();
                        showPage(page);
                    }
                });
            });

            // Booking form
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                e.preventDefault();
                openModal('paymentModal');
            });

            // Payment form
            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                processPayment();
            });

            // Duration change
            document.getElementById('durationSelect').addEventListener('change', function() {
                updateEstimatedCost();
            });

            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function(e) {
                handleSearch(e.target.value);
            });

            // History filter
            document.getElementById('historyFilter').addEventListener('change', function() {
                filterHistory(this.value);
            });

            // History search
            document.getElementById('historySearch').addEventListener('input', function(e) {
                searchHistory(e.target.value);
            });

            // Full History page search
            const fullHistorySearch = document.getElementById('fullHistorySearch');
            if (fullHistorySearch) {
                fullHistorySearch.addEventListener('input', function(e) {
                    searchFullHistory(e.target.value);
                });
            }

            // Full History page filter
            const fullHistoryFilter = document.getElementById('fullHistoryFilter');
            if (fullHistoryFilter) {
                fullHistoryFilter.addEventListener('change', function() {
                    filterFullHistory(this.value);
                });
            }

            // Add Vehicle Form
            document.getElementById('addVehicleForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitAddVehicle();
            });

            // Add Payment Form - Payment Type Change
            const paymentType = document.getElementById('paymentType');
            if (paymentType) {
                paymentType.addEventListener('change', function() {
                    togglePaymentFields(this.value, 'cardPaymentFields', 'ewalletFields', 'bankFields');
                });
            }

            // Add Payment Form Submit
            document.getElementById('addPaymentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitAddPayment();
            });

            // First Payment Setup - Payment Type Change
            const firstPaymentType = document.getElementById('firstPaymentType');
            if (firstPaymentType) {
                firstPaymentType.addEventListener('change', function() {
                    togglePaymentFields(this.value, 'firstCardFields', 'firstEwalletFields', null);
                });
            }

            // First Payment Form Submit
            document.getElementById('firstPaymentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitFirstPayment();
            });

        }

        function searchFullHistory(query) {
            const tbody = document.getElementById('fullHistoryTableBody');
            
            if (!query || query.trim() === '') {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                            <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                            <p>Enter a search term or select a filter to view your full parking history</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Show loading
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 3rem;">
                        <div class="loading"></div>
                        <p style="margin-top: 1rem; color: var(--text-secondary);">Searching...</p>
                    </td>
                </tr>
            `;
            
            // Fetch from API
            fetch(`/user/history?search=${encodeURIComponent(query)}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.history.length > 0) {
                    tbody.innerHTML = '';
                    data.history.forEach(log => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${log.date}</td>
                            <td>${log.slot}</td>
                            <td>${log.vehicle}</td>
                            <td>${log.entry_time || 'N/A'}</td>
                            <td>${log.exit_time || 'N/A'}</td>
                            <td>${log.duration}</td>
                            <td>${log.amount}</td>
                            <td><span class="parking-status status-completed">${log.status}</span></td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                <p>No results found for "${query}"</p>
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem; color: var(--danger-color);">
                            <p>Error loading history</p>
                        </td>
                    </tr>
                `;
            });
        }

        function filterFullHistory(filter) {
            const tbody = document.getElementById('fullHistoryTableBody');
            
            if (!filter) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                            <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                            <p>Enter a search term or select a filter to view your full parking history</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Show loading
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 3rem;">
                        <div class="loading"></div>
                        <p style="margin-top: 1rem; color: var(--text-secondary);">Loading...</p>
                    </td>
                </tr>
            `;
            
            // Fetch from API with filter
            fetch(`/user/history?filter=${encodeURIComponent(filter)}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.history.length > 0) {
                    tbody.innerHTML = '';
                    data.history.forEach(log => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${log.date}</td>
                            <td>${log.slot}</td>
                            <td>${log.vehicle}</td>
                            <td>${log.entry_time || 'N/A'}</td>
                            <td>${log.exit_time || 'N/A'}</td>
                            <td>${log.duration}</td>
                            <td>${log.amount}</td>
                            <td><span class="parking-status status-completed">${log.status}</span></td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                <p>No parking history for selected period</p>
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem; color: var(--danger-color);">
                            <p>Error loading history</p>
                        </td>
                    </tr>
                `;
            });
        }

        function updateEstimatedCost() {
            const duration = parseInt(document.getElementById('durationSelect').value);
            const rate = 3.00;
            const cost = (duration * rate).toFixed(2);
            document.getElementById('estimatedCost').value = `$${cost}`;
        }

        function selectPaymentMethod(method) {
            state.selectedPaymentMethod = method;
            
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            
            document.querySelector(`[data-method="${method}"]`).classList.add('selected');
            
            // Show/hide card details
            document.getElementById('cardDetails').style.display = method === 'card' ? 'block' : 'none';
        }

        function processPayment() {
            if (!state.selectedPaymentMethod) {
                showNotification('Please select a payment method', 'error');
                return;
            }

            if (!state.selectedSlot || !state.selectedSlot.id) {
                showNotification('Please select a parking slot', 'error');
                return;
            }

            showNotification('Processing payment...', 'info');
            
            const formData = new FormData();
            formData.append('slot_id', state.selectedSlot.id);
            formData.append('vehicle_id', document.getElementById('vehicleSelect').value);
            formData.append('duration_hours', document.getElementById('durationSelect').value);
            formData.append('payment_method', state.selectedPaymentMethod);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/user/book', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    closeModal('paymentModal');
                    closeModal('bookingModal');
                    refreshParkingGrid();
                    
                    // Update user status
                    state.currentUser.status = 'parked';
                    state.currentUser.currentParking = data.parking_session.slot_number;
                    updateOverviewCards();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Payment failed. Please try again.', 'error');
            });
        }

        function refreshParkingGrid() {
            showNotification('Refreshing parking status...', 'info');
            
            fetch('/user/slots', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    state.parkingSlots = data.slots;
                    generateParkingGrid();
                    updateParkingStats();
                    showNotification('Parking status updated!', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to refresh parking status', 'error');
            });
        }

        function extendParking() {
            showNotification('Extending parking time...', 'info');
            
            setTimeout(() => {
                showNotification('Parking time extended by 2 hours!', 'success');
            }, 1500);
        }

        function endParking() {
            if (confirm('Are you sure you want to end your parking session?')) {
                showNotification('Ending parking session...', 'info');
                
                setTimeout(() => {
                    clearInterval(state.timer);
                    state.currentUser.status = 'available';
                    state.currentUser.currentParking = null;
                    updateOverviewCards();
                    
                    showNotification('Parking session ended. Thank you!', 'success');
                    
                    // Add to history
                    addToHistory();
                }, 1500);
            }
        }

        function addToHistory() {
            const newRecord = {
                date: new Date().toISOString().split('T')[0],
                slot: state.currentUser.currentParking || 'A-15',
                duration: document.getElementById('timerDisplay').textContent,
                vehicle: 'ABC-123',
                amount: document.querySelector('.billing-value').textContent,
                status: 'completed'
            };
            
            state.history.unshift(newRecord);
            renderHistoryTable();
        }

        function updateOverviewCards() {
            // Update current parking card
            const parkingCard = document.querySelectorAll('.overview-card')[1];
            const status = parkingCard.querySelector('.parking-status');
            const value = parkingCard.querySelector('.overview-value');
            const label = parkingCard.querySelector('.overview-label');
            
            if (state.currentUser.status === 'parked') {
                status.className = 'parking-status status-parked';
                status.innerHTML = '<i class="fas fa-circle"></i> Parked';
                value.textContent = state.currentUser.currentParking;
                label.textContent = `Slot ${state.currentUser.currentParking} • Active`;
            } else {
                status.className = 'parking-status status-available';
                status.innerHTML = '<i class="fas fa-circle"></i> Available';
                value.textContent = 'No Active';
                label.textContent = 'Not Currently Parked';
            }
        }

        function showNotifications() {
            showNotification('You have 3 new notifications', 'info');
        }

        function exportHistory() {
            showNotification('Exporting history data...', 'info');
            
            setTimeout(() => {
                showNotification('History exported successfully!', 'success');
            }, 1000);
        }

        function exportFullHistory() {
            showNotification('Exporting full history to CSV...', 'info');
            
            setTimeout(() => {
                showNotification('Full history exported successfully!', 'success');
            }, 1500);
        }

        // Page Navigation
        function showPage(pageName) {
            // Hide all pages
            document.querySelectorAll('.page-section').forEach(page => {
                page.classList.add('hidden');
            });
            
            // Show selected page
            const targetPage = document.getElementById(`page-${pageName}`);
            if (targetPage) {
                targetPage.classList.remove('hidden');
            }
            
            // Update navigation active state
            document.querySelectorAll('.nav-item').forEach(nav => {
                nav.classList.remove('active');
                if (nav.dataset.page === pageName) {
                    nav.classList.add('active');
                }
            });
            
            // Close sidebar on mobile
            if (window.innerWidth <= 1024) {
                document.getElementById('sidebar').classList.remove('active');
            }
            
            // Page-specific initializations
            if (pageName === 'history') {
                document.getElementById('fullHistorySearch').focus();
            }
        }

        // Session Management
        function extendSession(sessionId) {
            showNotification('Extending parking session...', 'info');
            
            const formData = new FormData();
            formData.append('session_id', sessionId);
            formData.append('additional_hours', 2);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('/user/extend', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Refresh the page to show updated session
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to extend session', 'error');
            });
        }

        function endSession(sessionId) {
            if (!confirm('Are you sure you want to end this parking session?')) {
                return;
            }

            showNotification('Ending parking session...', 'info');

            const formData = new FormData();
            formData.append('session_id', sessionId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/user/end', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`Session ended! Final fee: $${data.final_fee}`, 'success');
                    // Refresh the page to update
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to end session', 'error');
            });
        }

        // Vehicle Management Functions
        function submitAddVehicle() {
            const vehicleType = document.getElementById('vehicleType').value;
            const licensePlate = document.getElementById('licensePlate').value;
            const make = document.getElementById('vehicleMake').value;
            const model = document.getElementById('vehicleModel').value;
            const color = document.getElementById('vehicleColor').value;

            if (!vehicleType || !licensePlate) {
                showNotification('Please fill in all required fields', 'error');
                return;
            }

            showNotification('Adding vehicle...', 'info');

            const formData = new FormData();
            formData.append('type', vehicleType);
            formData.append('license_plate', licensePlate);
            formData.append('make', make);
            formData.append('model', model);
            formData.append('color', color);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/user/vehicles', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Vehicle added successfully!', 'success');
                    closeModal('addVehicleModal');
                    // Reset form
                    document.getElementById('addVehicleForm').reset();
                    // Dynamically add vehicle to UI
                    addVehicleToUI(data.vehicle);
                } else {
                    showNotification(data.message || 'Failed to add vehicle', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add vehicle. Please try again.', 'error');
            });
        }

        function addVehicleToUI(vehicle) {
            // Add to vehicle select dropdown in booking modal
            const vehicleSelect = document.getElementById('vehicleSelect');
            const option = document.createElement('option');
            option.value = vehicle.id;
            option.textContent = `${vehicle.license_plate} - ${vehicle.make || ''} ${vehicle.model || ''}`;
            vehicleSelect.appendChild(option);

            // Add to vehicles page grid
            const vehiclesGrid = document.getElementById('vehiclesGrid');
            if (vehiclesGrid) {
                // Remove empty state message if it exists
                const noVehiclesMessage = document.getElementById('noVehiclesMessage');
                if (noVehiclesMessage) {
                    noVehiclesMessage.remove();
                }

                const vehicleCard = document.createElement('div');
                vehicleCard.className = 'overview-card';
                vehicleCard.style.animation = 'fadeIn 0.3s ease-out';
                vehicleCard.innerHTML = `
                    <div class="overview-header">
                        <div>
                            <div class="overview-title">${vehicle.license_plate}</div>
                            <div class="parking-status" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                                <i class="fas fa-check-circle"></i>
                                Active
                            </div>
                        </div>
                        <div class="overview-icon icon-primary">
                            <i class="fas fa-car-side"></i>
                        </div>
                    </div>
                    <div class="overview-value">${vehicle.make || ''} ${vehicle.model || ''}</div>
                    <div class="overview-label">Color: ${vehicle.color || 'N/A'} | Type: ${vehicle.type || 'N/A'}</div>
                `;
                vehiclesGrid.appendChild(vehicleCard);
            }

            // If this was the first vehicle, show welcome message
            if (vehicleSelect.options.length === 2) { // Only default option + new vehicle
                showNotification('You can now book parking slots with your new vehicle!', 'success');
            }
        }

        // Payment Management Functions
        function togglePaymentFields(type, cardFieldsId, ewalletFieldsId, bankFieldsId) {
            // Hide all field sections first
            if (cardFieldsId) document.getElementById(cardFieldsId).style.display = 'none';
            if (ewalletFieldsId) document.getElementById(ewalletFieldsId).style.display = 'none';
            if (bankFieldsId) document.getElementById(bankFieldsId).style.display = 'none';

            // Show selected type fields
            switch(type) {
                case 'card':
                    if (cardFieldsId) document.getElementById(cardFieldsId).style.display = 'block';
                    break;
                case 'ewallet':
                    if (ewalletFieldsId) document.getElementById(ewalletFieldsId).style.display = 'block';
                    break;
                case 'bank':
                    if (bankFieldsId) document.getElementById(bankFieldsId).style.display = 'block';
                    break;
            }
        }

        function submitAddPayment() {
            const paymentType = document.getElementById('paymentType').value;

            if (!paymentType) {
                showNotification('Please select a payment type', 'error');
                return;
            }

            // Validate fields based on type
            let isValid = true;
            let paymentData = { type: paymentType };

            if (paymentType === 'card') {
                const cardNumber = document.getElementById('cardNumber').value;
                const cardHolder = document.getElementById('cardHolder').value;
                const expiryDate = document.getElementById('expiryDate').value;
                const cvv = document.getElementById('cvv').value;

                if (!cardNumber || !cardHolder || !expiryDate || !cvv) {
                    isValid = false;
                    showNotification('Please fill in all card details', 'error');
                }
                paymentData = { ...paymentData, cardNumber, cardHolder, expiryDate, cvv };
            } else if (paymentType === 'ewallet') {
                const provider = document.getElementById('ewalletProvider').value;
                const account = document.getElementById('ewalletAccount').value;

                if (!provider || !account) {
                    isValid = false;
                    showNotification('Please fill in all e-wallet details', 'error');
                }
                paymentData = { ...paymentData, provider, account };
            } else if (paymentType === 'bank') {
                const bankName = document.getElementById('bankName').value;
                const bankAccount = document.getElementById('bankAccount').value;

                if (!bankName || !bankAccount) {
                    isValid = false;
                    showNotification('Please fill in all bank details', 'error');
                }
                paymentData = { ...paymentData, bankName, bankAccount };
            }

            if (!isValid) return;

            showNotification('Adding payment method...', 'info');

            const formData = new FormData();
            formData.append('payment_type', paymentType);
            formData.append('payment_data', JSON.stringify(paymentData));
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/user/payments', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Payment method added successfully!', 'success');
                    closeModal('addPaymentModal');
                    document.getElementById('addPaymentForm').reset();
                    // Hide all field sections
                    document.getElementById('cardPaymentFields').style.display = 'none';
                    document.getElementById('ewalletFields').style.display = 'none';
                    document.getElementById('bankFields').style.display = 'none';
                } else {
                    showNotification(data.message || 'Failed to add payment method', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add payment method. Please try again.', 'error');
            });
        }

        function submitFirstPayment() {
            const paymentType = document.getElementById('firstPaymentType').value;

            if (!paymentType) {
                showNotification('Please select a payment type', 'error');
                return;
            }

            let isValid = true;
            let paymentData = { type: paymentType };

            if (paymentType === 'card') {
                const cardNumber = document.getElementById('firstCardNumber').value;
                const cardHolder = document.getElementById('firstCardHolder').value;
                const expiryDate = document.getElementById('firstExpiryDate').value;
                const cvv = document.getElementById('firstCvv').value;

                if (!cardNumber || !cardHolder || !expiryDate || !cvv) {
                    isValid = false;
                    showNotification('Please fill in all card details', 'error');
                }
                paymentData = { ...paymentData, cardNumber, cardHolder, expiryDate, cvv };
            } else if (paymentType === 'ewallet') {
                const provider = document.getElementById('firstEwalletProvider').value;
                const account = document.getElementById('firstEwalletAccount').value;

                if (!provider || !account) {
                    isValid = false;
                    showNotification('Please fill in all e-wallet details', 'error');
                }
                paymentData = { ...paymentData, provider, account };
            }

            if (!isValid) return;

            showNotification('Setting up your payment method...', 'info');

            const formData = new FormData();
            formData.append('payment_type', paymentType);
            formData.append('payment_data', JSON.stringify(paymentData));
            formData.append('is_primary', true);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/user/payments', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Payment method added successfully! You can now book parking slots.', 'success');
                    closeModal('firstPaymentSetupModal');
                    // Refresh page to update state
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'Failed to add payment method', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add payment method. Please try again.', 'error');
            });
        }

        function checkFirstTimeUser() {
            // Check if user has any payment methods
            // For now, we'll check if they have any vehicles as an indicator
            // In a real app, you'd check for stored payment methods
            const hasVehicles = {{ $userVehicles->count() > 0 ? 'true' : 'false' }};

            // If user has no vehicles, they might be new - prompt for payment setup
            // You can adjust this logic based on your actual user data
            if (!hasVehicles) {
                // Show first payment setup after a short delay
                setTimeout(() => {
                    openModal('firstPaymentSetupModal');
                }, 1000);
            }
        }

        function handleSearch(query) {
            // Implement search functionality
            console.log('Searching for:', query);
        }

        function filterHistory(filter) {
            // Implement history filtering
            console.log('Filtering history:', filter);
        }

        function searchHistory(query) {
            if (!query || query.trim() === '') {
                state.history = [];
                state.historyLoaded = false;
                renderHistoryTable();
                return;
            }
            
            // Show loading state
            const tbody = document.getElementById('historyTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem;">
                        <div class="loading"></div>
                        <p style="margin-top: 1rem; color: var(--text-secondary);">Searching history...</p>
                    </td>
                </tr>
            `;
            
            // Fetch history from API with search query
            fetch(`/user/history?search=${encodeURIComponent(query)}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    state.history = data.history.map(log => ({
                        date: new Date(log.created_at).toLocaleDateString(),
                        slot: log.parking_slot.slot_number,
                        duration: calculateDuration(log.entry_time, log.exit_time),
                        vehicle: log.vehicle.license_plate,
                        amount: `$${parseFloat(log.total_fee).toFixed(2)}`,
                        status: log.status
                    }));
                    state.historyLoaded = true;
                    renderHistoryTable();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: var(--danger-color);">
                            <i class="fas fa-exclamation-circle" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                            <p>Failed to load history. Please try again.</p>
                        </td>
                    </tr>
                `;
            });
        }

        function calculateDuration(entry, exit) {
            const diff = new Date(exit) - new Date(entry);
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            return `${hours}h ${minutes}m`;
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            sidebar.classList.toggle('active');
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon();
        }

        function updateThemeIcon() {
            const icon = document.getElementById('themeIcon');
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function showNotification(message, type = 'info') {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            
            notification.innerHTML = `
                <div class="notification-header">
                    <div class="notification-title">
                        <i class="${icons[type]}"></i>
                        ${type.charAt(0).toUpperCase() + type.slice(1)}
                    </div>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="notification-message">${message}</div>
            `;
            
            container.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                showNotification('Logging out...', 'info');
                
                // Create form for logout
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/logout';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('active');
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.active').forEach(modal => {
                    modal.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>
